import fs from 'node:fs';
import path from 'node:path';
import { spawnSync } from 'node:child_process';

const root = process.cwd();
const qaDir = path.join(root, 'qa-results');
fs.mkdirSync(qaDir, { recursive: true });
const patterns = [];
for (const theme of ['beanstalk', 'beanstalk-child']) {
  const directory = path.join(root, theme, 'patterns');
  if (!fs.existsSync(directory)) continue;
  for (const file of fs.readdirSync(directory).filter((name) => name.endsWith('.php'))) {
    const source = fs.readFileSync(path.join(directory, file), 'utf8');
    const categories = source.match(/^\s*\*\s*Categories:\s*(.+)$/mi)?.[1] || '';
    const title = source.match(/^\s*\*\s*Title:\s*(.+)$/mi)?.[1]?.trim() || file;
    if (/hero/i.test(`${categories} ${title}`)) {
      patterns.push({ slug: path.basename(file, '.php'), title, content: source.replace(/<\?php[\s\S]*?\?>/g, '').trim() });
    }
  }
}

const user = process.env.WP_ADMIN_USER || 'qa-admin';
const password = process.env.WP_ADMIN_PASSWORD || 'qa-only-local-password';
const payload = Buffer.from(JSON.stringify({ user, password, patterns })).toString('base64');
const php = `
$data=json_decode(base64_decode('${payload}'),true);
switch_theme('beanstalk-child');
$existing=get_user_by('login',$data['user']);
if(!$existing){wp_create_user($data['user'],$data['password'],$data['user'].'@example.invalid');$existing=get_user_by('login',$data['user']);}
$existing->set_role('administrator');wp_set_password($data['password'],$existing->ID);
$home=get_page_by_path('qa-home');
$home_content='<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Beanstalk QA</h1><!-- /wp:heading --><!-- wp:paragraph --><p>Automated theme test page.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Test button</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
$home_id=wp_insert_post(['ID'=>$home?$home->ID:0,'post_title'=>'Beanstalk QA','post_name'=>'qa-home','post_type'=>'page','post_status'=>'publish','post_content'=>$home_content]);
update_option('show_on_front','page');update_option('page_on_front',$home_id);
$result=['front'=>'http://localhost:8888/','heroes'=>[]];
foreach($data['patterns'] as $pattern){$post=get_page_by_path('qa-'.$pattern['slug']);$id=wp_insert_post(['ID'=>$post?$post->ID:0,'post_title'=>'QA '.$pattern['title'],'post_name'=>'qa-'.$pattern['slug'],'post_type'=>'page','post_status'=>'publish','post_content'=>$pattern['content']]);$result['heroes'][]=['slug'=>$pattern['slug'],'title'=>$pattern['title'],'id'=>$id,'url'=>'http://localhost:8888/?page_id='.$id];}
echo 'QA_MANIFEST:'.base64_encode(wp_json_encode($result));`;
const run = spawnSync('npx', ['wp-env', 'run', 'cli', 'wp', 'eval', php], { cwd: root, encoding: 'utf8' });
if (run.status !== 0) throw new Error(run.stderr || run.stdout || 'wp-env preparation failed');
const encoded = run.stdout.match(/QA_MANIFEST:([A-Za-z0-9+/=]+)/)?.[1];
if (!encoded) throw new Error(`wp-env did not return a QA manifest:\n${run.stdout}\n${run.stderr}`);
fs.writeFileSync(path.join(qaDir, 'urls.json'), `${Buffer.from(encoded, 'base64').toString('utf8')}\n`);
console.log(fs.readFileSync(path.join(qaDir, 'urls.json'), 'utf8'));
