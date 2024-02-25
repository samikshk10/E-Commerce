@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>


<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Edit Quick Reply</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Quick Reply</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row">

                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">

                            <form id="myForm" method="post" action="{{ route('update.quickreply') }}" >
                                @csrf
                                <input type="hidden" name="id" value="{{ $quickreply->id }}">

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Quick Reply Text</h6>
                                </div>
                                <div class="form-group col-sm-9 text-secondary">
                                    <textarea  name="quickreplytext" id="quickreplytext" class="form-control" rows="10" maxlength="255">{{ $quickreply->quickreplytext }}</textarea>
                                    <span id="max-text" class="text-right">Max 255 Character (<span id="textcount">0</span>/255)</span>

                                   
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9 text-secondary">
                                    <input type="submit" class="btn btn-primary px-4" value="Edit Quick Reply" />
                                </div>
                            </div>
                        </div>

                    </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    $(document).ready(function(){
        var textlength= $('#quickreplytext').val().length;
        $('#textcount').text(textlength);
        $('#myForm').validate({
            rules:{
                quickreplytext:{
                    required : true,
                },
            },
            messages:{
                quickreplytext:{
                    required: 'Please enter quick reply text',
                },
            },
          
            errorElement : 'span',
            errorPlacement: function(error, element){
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });


        var minLength = 0;
        $('#quickreplytext').keydown(function() {
          var textlen = minLength + $(this).val().length;
          $('#textcount').text(textlen);
        });
    });


</script>

@endsection