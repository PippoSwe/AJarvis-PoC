<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="report-container">
	<div class="row">
		<div class="col-sm-12">
			<p class="h3">Testo analizzato:</p>
			<?php echo $plain_text; ?>
		</div>
	</div>
	<div class="mt-3"></div>
	<div class="row">
		<?php if(count($sentences_positive['sentences']) > 0) : ?>
			<div class="col-lg">
				<p class="h3">Frasi positive:</p>
				<?php echo $this->load->view('analyzer/sentencesTable', array('sentences'=>$sentences_positive,'type'=>'positive'), TRUE); ?>
			</div>
		<?php endif; ?>
		<?php if(count($sentences_negative['sentences']) > 0) : ?>
			<div class="col-lg">
				<p class="h3">Frasi negative:</p>
				<?php echo $this->load->view('analyzer/sentencesTable', array('sentences'=>$sentences_negative,'type'=>'negative'), TRUE); ?>
			</div>
		<?php endif; ?>
		<?php if(count($sentences_neutral['sentences']) > 0) : ?>
			<div class="col-lg">
				<p class="h3">Frasi neutrali:</p>
				<?php echo $this->load->view('analyzer/sentencesTable', array('sentences'=>$sentences_neutral,'type'=>'neutral'), TRUE); ?>
			</div>
		<?php endif; ?>
	</div>
	<p class="h3">Tipi individuati:</p>
	<div class="row">
		<div class="col-md">
		    <?php echo $this->load->view('charts/barChart.php', $barChart, TRUE); ?>
		</div>
		<div class="col-md">
		    <?php echo $this->load->view('charts/donutChart.php', $pieChart, TRUE); ?>
		</div>
	</div>
	<p class="h3">Tipi individuati con maggiore dettaglio:</p>
	<div class="row">
		<div class="col-md">
	    	<?php echo $this->load->view('charts/barChart.php', $strictBarChart, TRUE); ?>
	    </div>
	    <div class="col-md">
	    	<?php echo $this->load->view('charts/donutChart.php', $strictPieChart, TRUE); ?>
	    </div>
	</div>
	<div class="row">
		<div class="col-sm-12">
	    	<p class="h3">Andamento del discorso:</p>
	    	<?php echo $this->load->view('charts/thresholdChart.php', $speechThreshold, TRUE); ?>
	    </div>
	</div>
</div>
