<?php
/**
 * Curated Rank Math llms.txt content.
 *
 * @package BeanstalkChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Removes Rank Math's generic credit line from the curated response.
 *
 * @return bool
 */
function white_oaks_llms_remove_credit() {
	return true;
}
add_filter( 'rank_math/llms_txt/remove_credit', 'white_oaks_llms_remove_credit' );

/**
 * Prevents a second, automatically generated page list.
 *
 * @return array
 */
function white_oaks_llms_remove_generated_posts() {
	return array();
}
add_filter( 'rank_math/llms_txt/posts', 'white_oaks_llms_remove_generated_posts' );

/**
 * Supplies the authoritative business and patient-information summary.
 *
 * @return string
 */
function white_oaks_llms_content() {
	$home = untrailingslashit( home_url( '/' ) );

	return <<<LLMS
White Oaks Mall Dental is a family dental clinic in London, Ontario. The clinic provides preventive, cosmetic, restorative, orthodontic, implant, surgical, sedation, and emergency dental care. It welcomes new patients of all ages and offers evening and Saturday appointments.

## Services
- General and family dentistry: dental exams, cleanings, scaling, night guards, and pediatric dentistry.
- Cosmetic dentistry: Zoom whitening, veneers, dental bonding, crowns, bridges, Invisalign, orthodontics, and retainers.
- Restorative and implant dentistry: fillings, dentures, denture repair, dental implants, overdentures, and All-on-4 dental implants.
- Emergency and surgical dentistry: same-day emergency care when available, tooth extractions, surgical extractions, wisdom teeth removal, root canal treatment, and root canal retreatment.
- IV sedation dentistry for eligible root canal, implant, extraction, and wisdom-teeth procedures.

## Service Area
White Oaks Mall Dental serves patients in London, Ontario and surrounding communities from its clinic at 1105 Wellington Road, London, Ontario N6E 1V4.

## Patient Process
1. Book an appointment online or call the care team at (519) 686-6200.
2. Share your health history, concerns, symptoms, and treatment goals.
3. The dental team assesses your teeth, gums, bite, and any clinically necessary records or imaging.
4. Your dentist explains suitable treatment options, timing, estimated fees, insurance information, and next steps.
5. Treatment proceeds only after the plan has been reviewed with you; follow-up and ongoing preventive care are recommended according to your needs.

## Key Pages
- [Home]({$home}/): Clinic overview, services, FAQs, locations, and patient stories.
- [White Oaks Mall clinic]({$home}/locations/white-oaks-mall/): Address, hours, amenities, and appointment information.
- [Invisalign]({$home}/services/invisalign/): Clear-aligner treatment, first-visit process, fees, insurance, financing, and FAQs.
- [Meet Our Doctors]({$home}/meet-our-doctors/): Dentist biographies and areas of care.
- [Contact and Appointments]({$home}/contact-us/): Appointment request form, phone, email, and clinic details.
- [Privacy Policy]({$home}/privacy-policy/): Website privacy practices.

## Frequently Asked Questions
### What happens at a first visit with a new dentist?
Your dentist reviews your health history, examines your teeth and gums, and discusses your concerns. X-rays or a cleaning may be recommended based on your needs.

### Do you direct bill dental insurance and accept the Canadian Dental Care Plan?
Yes. The clinic offers direct billing to many dental insurance plans and accepts the Canadian Dental Care Plan. The team can help patients understand their coverage.

### Are you accepting new patients?
Yes. White Oaks Mall Dental welcomes new patients of all ages.

### Do you offer same-day emergency appointments?
Same-day emergency dental appointments are offered when availability allows. Patients should call as soon as possible for an assessment and next-step guidance.

### How long does a checkup and cleaning take?
A checkup and cleaning usually takes about 45 to 60 minutes, depending on oral health, treatment needs, and whether X-rays are required.

### How often should I book a checkup and cleaning?
Many patients benefit from a checkup and cleaning about every six months. A dentist may recommend a different schedule based on individual oral health and risk factors.

## Review Summary
The website reports a 4.9 rating and more than 800 five-star Google reviews. Featured patient feedback highlights clear explanations of treatment options, caring and professional staff, gentle care, help understanding costs and insurance, and comfortable appointments. Individual experiences and treatment outcomes vary.
LLMS;
}
add_filter( 'rank_math/llms_txt/extra_content', 'white_oaks_llms_content' );
