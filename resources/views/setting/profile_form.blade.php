<div class="col-md-12">
    <div class="row ">
		<div class="col-md-3 {{ auth()->user()->hasRole('doctor') ? 'd-none' : '' }}">
			<div class="user-sidebar">
				<div class="user-body user-profile text-center">
					<div class="user-img">
						<img class="rounded-circle avatar-100 image-fluid profile_image_preview" src="{{ auth()->user()->hasRole('doctor') ? getSingleMedia($health_expert_data,'health_experts_image', null) : getSingleMedia($user_data,'profile_image', null) }}" alt="profile-pic">
					</div>
					<div class="sideuser-info">
						<span class="mb-2">{{ $user_data->display_name }}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-{{ auth()->user()->hasRole('doctor') ? '12' : '9' }}">
			<div class="user-content">
				@if (auth()->user()->hasRole('doctor'))
						{{ html()->modelForm($health_expert_data, 'POST', route('updateHealthExpertProfile'))->attribute('data-toggle', 'validator')->enctype('multipart/form-data')->id('user-form') }}
						<input type="hidden" name="profile" value="profile">
						{{ html()->hidden('username') }}
						{{ html()->hidden('email', optional($health_expert_data->users)->email ?? null) }}
						{{ html()->hidden('id')->placeholder('id')->class('form-control') }}
						<div class="row ">
							
							<div class="form-group col-md-6">
								{{ html()->label(__('message.name').' <span class="text-danger">*</span>', 'name')->class('form-control-label')->for('name') }}
								{{ html()->text('name', isset($health_expert_data) ? optional($health_expert_data->users)->first_name : null)->placeholder(__('message.name'))->class('form-control')->attribute('autofocus') }}
							</div>
							<div class="form-group col-md-6">
								{{ html()->label(__('message.email').' <span class="text-danger">*</span>', 'email')->class('form-control-label')->for('email') }}
								{{ html()->text('email', isset($health_expert_data) ? optional($health_expert_data->users)->email : null)->placeholder(__('message.email'))->class('form-control')->attribute('disabled') }}
							</div>
							<div class="form-group col-md-6">
								{{ html()->label(__('message.tag_line').' <span class="text-danger">*</span>', 'tag_line')->class('form-control-label')->for('tag_line') }}
								{{ html()->text('tag_line', old('tag_line'))->placeholder(__('message.tag_line'))->class('form-control') }}
							</div>
							<div class="form-group col-md-{{ isset($health_expert_data) && getMediaFileExit($health_expert_data, 'health_experts_image') ? '4' : '6' }}">
								{{ html()->label(__('message.choose_profile_image'), 'profile_image')->class('form-control-label col-md-12') }}
								<div class="custom-file">
									{{ html()->file('health_experts_image')->class('custom-file-input custom-file-input-sm detail')->id('profile_image')->accept('image/*')->attribute('lang', 'en') }}
									<label class="custom-file-label" id="imagelabel" for="profile_image">{{ __('message.profile_image') }}</label>
								</div>
							</div>

							@if( isset($health_expert_data) && getMediaFileExit($health_expert_data, 'health_experts_image'))
								<div class="col-md-2 mb-2">
									<img id="health_experts_image_preview" src="{{ getSingleMedia($health_expert_data,'health_experts_image') }}" alt="health-experts-image" class="attachment-image mt-1 mr-1">
									<a class="text-danger remove-file" href="{{ route('remove.file', ['id' => $health_expert_data->id, 'type' => 'health_experts_image']) }}"
										data--submit='confirm_form'
										data--confirmation='true'
										data--ajax='true'
										data-toggle='tooltip'
										title='{{ __("message.remove_file_title" , ["name" =>  __("message.image") ]) }}'
										data-title='{{ __("message.remove_file_title" , ["name" =>  __("message.image") ]) }}'
										data-message='{{ __("message.remove_file_msg") }}'>
										<i class="ri-close-circle-line"></i>
									</a>
								</div>
							@endif
							
							<div class="form-group col-md-6">
								{{ html()->label(__('message.short_description') . ' <span class="text-danger">*</span>', 'short_description')->class('form-control-label font-weight-bold') }}
								{{ html()->textarea('short_description')->class('form-control tinymce-description')->placeholder(__('message.short_description'))->rows(3)->cols(40) }}
							</div>


							<div class="form-group col-md-6">
								{{ html()->label(__('message.career'), 'career')->class('form-control-label font-weight-bold') }}
								{{ html()->textarea('career')->class('form-control tinymce-description')->placeholder(__('message.career'))->rows(3)->cols(40) }}
							</div>

							<div class="form-group col-md-6">
								{{ html()->label(__('message.education'), 'education')->class('form-control-label font-weight-bold') }}
								{{ html()->textarea('education')->class('form-control tinymce-description')->placeholder(__('message.education'))->rows(3)->cols(40) }}
							</div>

							<div class="form-group col-md-6">
								{{ html()->label(__('message.awards_achievements'), 'awards_achievements')->class('form-control-label font-weight-bold') }}
								{{ html()->textarea('awards_achievements')->class('form-control tinymce-description')->placeholder(__('message.awards_achievements'))->rows(3)->cols(40) }}
							</div>

							<div class="form-group col-md-6">
								{{ html()->label(__('message.area_expertise'), 'area_expertise')->class('form-control-label font-weight-bold') }}
								{{ html()->textarea('area_expertise')->class('form-control tinymce-description')->placeholder(__('message.area_expertise'))->rows(3)->cols(40) }}
							</div>
							<div class="col-md-12">
								{{ html()->button(__('message.update'))->class('btn btn-md btn-primary float-md-right')->type('submit') }}
							</div>
						</div>
				@else
                    {{ html()->modelForm($user_data, 'POST', route('updateProfile'))->attribute('enctype', 'multipart/form-data')->attribute('data-toggle','validator')->open() }}
						<input type="hidden" name="profile" value="profile">
						{{ html()->hidden('username') }}
						{{ html()->hidden('email') }}
						{{ html()->hidden('id')->class('form-control')->placeholder('id') }}

						<div class="row ">

							<div class="form-group col-md-6">
								{{ html()->label(__('message.first_name') . ' <span class="text-danger">*</span>')->for('first_name')->class('form-control-label') }}
								{{ html()->text('first_name', old('first_name'))->placeholder(__('message.first_name'))->class('form-control')->required() }}
							</div>

							<div class="form-group col-md-6">
								{{ html()->label(__('message.last_name') . ' <span class="text-danger">*</span>')->for('last_name')->class('form-control-label') }}
								{{ html()->text('last_name', old('last_name'))->placeholder(__('message.last_name'))->class('form-control')->required() }}
							</div>

							<div class="form-group col-md-6">
								{{ html()->label(__('message.email') . ' <span class="text-danger">*</span>')->for('email')->class('form-control-label') }}   
								{{ html()->email('email', old('email'))->placeholder(__('message.email'))->class('form-control')->required()->disabled() }}
							</div>

							<div class="form-group col-md-6">
								{{ html()->label(__('message.choose_profile_image'))->for('profile_image')->class('form-control-label col-md-12') }}
									
								<div class="custom-file">
									{{ html()->file('profile_image')->class('custom-file-input custom-file-input-sm detail')->id('profile_image')->attribute('lang', 'en')->accept('image/*') }}

									<label class="custom-file-label" id="imagelabel" for="profile_image">
										{{ __('message.profile_image') }}
									</label>
								</div>
							</div>

							<div class="form-group col-md-12">
								{{ html()->label(__('message.address'))->for('address')->class('form-control-label') }}
								{{ html()->textarea('address', null)->class('form-control textarea')->rows(3)->placeholder(__('message.address')) }}
							</div>
							<div class="col-md-12">
								{{ html()->submit(__('message.update'))->class('btn btn-md btn-primary float-md-right') }}
							</div>
						</div>
				@endif
			</div>
		</div>
    </div>
</div>

<script>
	$(document).ready(function (){
		tinymce.init({
			selector: '.tinymce-description',
			menubar: false,
			plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
				'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
				'insertdatetime', 'media', 'table', 'help', 'wordcount'
			],
			toolbar: 'undo redo | blocks | bold italic | bullist numlist outdent indent | removeformat | help',
			height: 300,
			branding: false
		});		
        $(document).on('change','#profile_image',function(){
			readURL(this);
		})
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				var res=isImage(input.files[0].name);

				if(res==false){
					var msg = "{{ __('message.image_png_gif') }}";
					Snackbar.show({text: msg ,pos: 'bottom-center',backgroundColor:'#d32f2f',actionTextColor:'#fff'});
					return false;
				}

				reader.onload = function(e) {
				$('.profile_image_preview').attr('src', e.target.result);
					$("#imagelabel").text((input.files[0].name));
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		function getExtension(filename) {
			var parts = filename.split('.');
			return parts[parts.length - 1];
		}

		function isImage(filename) {
			var ext = getExtension(filename);
			switch (ext.toLowerCase()) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
				return true;
			}
			return false;
		}

		var input = document.querySelector("#phone"), 
		errorMsg = document.querySelector("#error-msg"),
		validMsg = document.querySelector("#valid-msg");

		if(input) {
			var iti = window.intlTelInput(input, {
				hiddenInput: "contact_number",
				separateDialCode: true,
				utilsScript: "{{ asset('vendor/intlTelInput/js/utils.js') }}" // just for formatting/placeholders etc
			});

			input.addEventListener("countrychange", function() {
				validate();
			});

			// // here, the index maps to the error code returned from getValidationError - see readme
			var errorMap = [ "Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];
			//
			// // initialise plugin
			const phone = $('#phone');
			const err = $('#error-msg');
			const succ = $('#valid-msg');
			var reset = function() {
				err.addClass('d-none');
				succ.addClass('d-none');
				validate();
			};

			// on blur: validate
			$(document).on('blur, keyup','#phone',function () {
				reset();
				var val = $(this).val();
				if (val.match(/[^0-9\.\+.\s.]/g)) {
					$(this).val(val.replace(/[^0-9\.\+.\s.]/g, ''));
				}
				if(val === ''){
					$('[type="submit"]').removeClass('disabled').prop('disabled',false);
				}
			});

			// on keyup / change flag: reset
			input.addEventListener('change', reset);
			input.addEventListener('keyup', reset);

			var errorCode = '';

			function validate() {
				if (input.value.trim()) {
					if (iti.isValidNumber()) {
						succ.removeClass('d-none');
						err.html('');
						err.addClass('d-none');
						$('[type="submit"]').removeClass('disabled').prop('disabled',false);
					} else {
						errorCode = iti.getValidationError();
						err.html(errorMap[errorCode]);
						err.removeClass('d-none');
						phone.closest('.form-group').addClass('has-danger');
						$('[type="submit"]').addClass('disabled').prop('disabled',true);
					}
				}
			}
		}
	})
</script>