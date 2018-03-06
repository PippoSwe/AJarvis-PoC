<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center mt-3">
	<div class="col-sm-12 col-lg-6 recorder-container">
		<button class="record btn btn-primary"><i class="ion-android-microphone mr-1"></i> registra</button>
		<button class="stop btn btn-outline-danger"><i class="ion-stop mr-1"></i> stop</button>
		<a class="btn btn-outline-success  float-right" id="visualizeReport" href="<?php echo site_url('analyzer'); ?>" target="_blank">
			<i class="ion-ios-analytics-outline d-none mr-1"></i>visualizza report
		</a>
		<div id="timer"></div>
	</div>
</div>
<div class="loader-container d-none">
	<div class="row justify-content-center mt-3 text-center">
		<div class="col-sm-12 col-lg-6">
			Sto elaborando il file.. attendere
			<div class="loader">
				<div></div><div></div><div></div>
			</div>
		</div>
	</div>
</div>
<div class="row justify-content-center mt-3">
	<div class="col-sm-12 col-lg-6">
		<div class="progress d-none">
	  		<div class="progress-bar progress-bar-striped progress-bar-animated"  role="progressbar" id="uploadProgress" style="width: 50%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>
	</div>
</div>
<div class="row justify-content-center mt-3">
	<div class="col-sm-12 col-lg-6" id="downloadContainer">
	</div>
</div>