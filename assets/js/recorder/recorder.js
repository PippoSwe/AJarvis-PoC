// set up basic variables for app

var record = document.querySelector('.record');
var stop   = document.querySelector('.stop');

// disable stop button while not recording

stop.disabled = true;

var audioCtx  = new (window.AudioContext || webkitAudioContext)();

//main block for doing the audio recording

if (navigator.mediaDevices.getUserMedia) {
	var constraints = { audio: true };
	var chunks      = [];
	var blobs       = [];
	var blobs_index = 0;

  	var onSuccess = function(stream) {
		var mediaRecorder = new MediaRecorder(stream);

		visualize(stream);

		record.onclick = function() {
			mediaRecorder.start();

			stop.disabled   = false;
			record.disabled = true;
		}

		stop.onclick = function() {
			stop.disabled   = true;
			record.disabled = false;

			mediaRecorder.stop();
		}

		mediaRecorder.onstop = function(e) {
			var clipLabel     = document.createElement('p');

			var blob = new Blob(chunks, { 'type' : 'audio/ogg; codecs=opus' });
			chunks = [];
			var audioURL = window.URL.createObjectURL(blob);
			addAudioElement(audioURL, blob.size);

			blobs.push(blob);
			blobs_index++;
		}

		mediaRecorder.ondataavailable = function(e) {
		  	chunks.push(e.data);
		}
  	}

  	var onError = function(err) {
		console.log('The following error occured: ' + err);
  	}

  	navigator.mediaDevices.getUserMedia(constraints).then(onSuccess, onError);
} else {
   console.log('getUserMedia not supported on your browser!');
}

function visualize(stream) {
	var source       = audioCtx.createMediaStreamSource(stream);
	var analyser     = audioCtx.createAnalyser();
	analyser.fftSize = 2048;
	var bufferLength = analyser.frequencyBinCount;
	var dataArray    = new Uint8Array(bufferLength);

	source.connect(analyser);
}

function addAudioElement(blobUrl, size)
{
	// calculate KB or MB for file size
	size /= 1024;

	if(size > 1024) {
		size /= 1024;
		size = Math.round(size, 1);
		size = '(' + size + ' MB) ';
	}
	else {
		size = Math.round(size, 1);
		size = '(' + size + ' KB) ';
	}

	var desc = new Date().toLocaleString() + ' ' + size;
	var newAudio = '<div class="audio-element">' + desc + '<div class="controls"><button type="button" class="btn btn-sm btn-success save-element" data-blob="' + blobUrl + '" data-index="' + blobs_index + '"><i class="ion-ios-checkmark"></i> SALVA</button><button class="btn btn-sm btn-danger delete-element"><i class="ion-ios-close"></i> SCARTA</button></div></div>';

	$('#downloadContainer').append(newAudio);
}

function createAudioElement(url, blob) {
	var filename = 'audio_file.wav';
	var data     = new FormData();
	var success  = true;

	data.append('file', blob);
console.log(location);
	$.ajax({
		url :  location.origin + '/recorder/save_audio',
		type: 'POST',
		data: data,
		contentType: false,
		processData: false,
		error: function() {
			success = false;
		}
	});

	return success;
}

$('body').on('click','.save-element',function(){
	var _this = $(this);

	if(createAudioElement(_this.data('blob'), blobs[_this.data('index')])){
		_this.closest('.controls').html('<em>Salvato</em>');
	}
	else {
		_this.closest('.controls').html('<em>Impossibile salvare il file registrato</em>');
	}
});

$('body').on('click','.delete-element',function(){
	$(this).closest('.audio-element').remove();
});