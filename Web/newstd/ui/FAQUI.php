<script>
	$(function() {
		$( "#accordion" ).accordion({
			heightStyle: "content"
		});
	});
</script>

<div class="post">
	<h2>New Students</h2>
	<?php include __UI_DIR_PATH . '/NewstdMenuUI.php'; ?>
	<div class="post-item">
		<h2><?= htmlspecialchars($pageTitle) ?>s - all the most frequently asked questions</h2>
		<div class="clear-float"></div>
		<div class="post-image"><img src="<?= __UI_DIR_URL ?>/images/blue_talk_to_students.jpg" alt="Img"></div>

		<div id="accordion">
			<h3>Pre-enrolment</h3>
			<div>
				<p><b>Do I need to pre-enrol?</b></p>
				<p>Yes, all students should complete pre-enrolment before arriving at UoG to enrol.</p>

				<p><b>I've just accepted my offer - can I pre-enrol straight away?</b></p>
				<p>You may pre-enrol as soon as you receive an email advising you that the service is open.</p>

			</div>

			<h3>Enrolment</h3>
			<div>
				<p><b>Do you require a visa to enter or stay in the United Kingdom?</b></p>
				<p>A visa is required only by students who are not from the UK or the EU and who need to have a visa to enter and live in the UK. Each country has different requirements. You can check the UKBA Website or the International Students Support webpages in order to get more information. If you have any problems you can contact UoGU Rights & Advice who are qualified to provide immigration advice.</p>

				<p><b>Do I need a visa to enrol at UoG?</b></p>
				<p>If you require a visa to study in the UK, you must show your valid visa when you arrive at UoG to enrol. Generally, we would expect you to arrive with a valid Tier 4 General Student visa for the duration of your programme of study which has been associated with UoG's sponsor licence number. For students who are studying for less than six months with us, or if you are joining an English for Academic Purposes course at the UoG Centre for Languages and International Education (CLIE), we can accept a Student Visitor Visa. We will not be able to complete your registration at UoG without you providing us with proof of a valid visa so it is extremely important that you obtain one as early as possible in order to avoid any delays in your enrolment process. Further information about visas is available on the International Students Support webpages.</p>

				<p><b>Where and when do I enrol?</b></p>
				<p>Please read through the information on our <a href="<?= __SITE_CONTEXT ?>/newstd/enrolment">Enrolment page</a> for the answer to your question.</p>
			</div>

			<h3>Pay Your Fees</h3>
			<div>
				<p><b>How can I pay my fees?</b></p>
				<p>Please read through the information on our <a href="<?= __SITE_CONTEXT ?>/newstd/payYourFees">Pay your Fees page</a> for the answer to your question.</p>
				<p><b>I am having problems paying my fees online.</b></p>
				<p>All fees related enquiries should be directed to the Student Fees Office, Room G19, South Wing, or you can contact them by email at <a href="mailto:fees@uog.ac.uk">fees@uog.ac.uk</a>. The office is open Monday - Friday between 10am and 4pm.</p>
			</div>

			<h3>Get Your ID</h3>
			<div>
				<p><b>I've lost my new ID card!</b></p>
				<p>If a card is lost or stolen during its period of validity the first replacement will be free but a £15 administration fee will be charged on any subsequent occasion where a replacement is requested for reasons other than card expiry. In such circumstances a voucher must be bought from the UoG shop and then be taken to Security Systems in the Andrew Huxley Building, where a new card will be issued. The office is open Monday to Friday from 9am to 4.45pm. Please bring one form of personal identification (e.g. bank card, driving licence, etc.) You do not need to bring a photo. Security Systems will take your photograph before your card is issued.</p>
				<p><b>Do I need to bring a photo for my ID card?</b></p>
				<p>No – the picture is taken there and then by Security Services.</p>
			</div>

			<h3>Get Connected</h3>
			<div>
				<p><b>Where can I find computers that I can use?</b></p>
				<p>You can use this map of UoG Bloomsbury Campus cluster rooms.</p>
				<p><b>Where can I get help with computer issues?</b></p>
				<p>In most cases you will need to contact the ISD Service Desk.</p>
			</div>

		</div>
	</div>

</div>
