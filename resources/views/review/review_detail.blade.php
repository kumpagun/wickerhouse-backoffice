@extends('layouts.app')

@php $title = strtoupper('แบบประเมินหลักสูตรออนไลน์'); @endphp

@section('content-header-left')
<h3 class="content-header-title mb-2">{{ $title }}</h3>
<div class="row breadcrumbs-top">
  <div class="breadcrumb-wrapper col-12">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('course_create', ['id' => $review_group->course_id, '#review']) }}">Course</a></li>
      <li class="breadcrumb-item active">{{ $title }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
  <div class="col-12 text-center">
   
  </div>
</div>
@endsection

@section('script')
  <script src="{{ asset('stack-admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}" type="text/javascript"></script>
  <script>
  function handleDeleteReviewUrl(review_group_id) {
    url = "{{ route('review_group_delete') }}/"+review_group_id
    swal({
      title: "คุณต้องการลบ Review URL ใช่หรือไม่ ?",
      icon: "warning",
      showCancelButton: true,
      buttons: {
        cancel: {
          text: "ยกเลิก",
          value: null,
          visible: true,
          className: "",
          closeModal: true,
        },
        confirm: {
          text: "ลบ",
          value: true,
          visible: true,
          className: "",
          closeModal: false
        }
      }
    }).then(isConfirm => {
      if (isConfirm) {
        window.location = url
      } 
    });
  }
  function hideModal() {
    $("[data-dismiss=modal]").trigger({ type: "click" });
  }
  // Custom Show / Hide Configurations
  $('.choice-repeater, .answerchoice-repeater').repeater({
    show: function () {
      $(this).slideDown();
    },
    hide: function(remove) {
      if (confirm('Are you sure you want to remove this item?')) {
        $(this).slideUp(remove);
      }
    }
  });
  </script>
@endsection