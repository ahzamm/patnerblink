<!--
* This file is part of the SQUADCLOUD project.
*
* (c) SQUADCLOUD TEAM
*
* This file contains the configuration settings for the application.
* It includes database connection details, API keys, and other sensitive information.
*
* IMPORTANT: DO NOT MODIFY THIS FILE UNLESS YOU ARE AN AUTHORIZED DEVELOPER.
* Changes made to this file may cause unexpected behavior in the application.
*
* WARNING: DO NOT SHARE THIS FILE WITH ANYONE OR UPLOAD IT TO A PUBLIC REPOSITORY.
*
* Website: https://squadcloud.co
* Created: September, 2024
* Last Updated: 01th September, 2024
* Author: SquadCloud Team <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<style>
  input[type=file][hidden] {
    display: none
  }
  .document-file{
    display: flex;
    align-items: flex-end;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    justify-content: space-between;
    gap: 0.25rem;
  }
  .text_description{
    display:none;
  }
</style>
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    @include('users.layouts.session')
    <section class="wrapper main-wrapper row">
      <div class="alert alert-danger print-error-msg" style="display:none" >
        <ul></ul>
      </div>
      <div class="alert alert-success success-msg" style="display:none" >
        <ul></ul>
      </div>
      <div class="header_view">
        <h2>Upload Commercials
          <span class="info-mark" onmouseenter="popup_function(this, 'commercials_upload');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box ">
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
        </div>
        @endif
        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">×</button> 
          <strong>{{ $message }}</strong>
        </div>
        @endif
        <div class="content-body" style="padding-top:20px">
          <form id="marketing" action="{{route('users.marketing.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label>Select Reseller <span style="color:red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'commercials_select_reseller');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select name="resellerid" id="" class="form-control" required>
                    <option value="">select reseller</option>
                    @foreach($reseller_data as $data)
                    <option value="{{$data->resellerid}}">{{$data->resellerid}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label>Marketing Category <span style="color:red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'commercials_select_category');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select id="mark_cat" class="form-control" name="category">
                    <option value="">Select Category</option>
                    <option value="Social Media Post">Social Media Post</option>
                    <option value="Promotions Video">Promotions Video</option>
                    <option value="Broucher">Broucher</option>
                    <option value="Billboard">Billboard</option>
                    <option value="Tips and Tricks">Tips and Tricks</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4 text_description">
                <div class="form-group">
                  <label>Short Description <span style="color:red">*</span></label>
                  <input type="text" class="form-control" placeholder="Short Description" name="short_description">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <div class="section-container py-10 min-h-1/2">
                    <div class="flex flex-col items-center">
                      <div class="mt-6 py-3 bg-white rounded-lg w-full md:w-1/2 lg:w-1/2">
                        <div class="mt-5 px-3 flex gap-6" style="display:flex; gap: 20px">
                          <div class="flex-1 flex flex-col items-center p-3 border-2 border-dotted border-gray-300 rounded-lg drag-area" style="border:2px dotted #777;padding:0.75rem;border-radius:0.5rem;align-items:center; flex-direction:column; flex:1 1 0%;display: flex"><i class="fas fa-cloud-upload-alt" style="font-size: 3rem;line-height:1;color: #0d4dab"></i>
                            <div class="mt-6" style="margin-top: 1.5rem">
                              <span class="drag-file">Drag files here to upload </span> or
                              <button class="px-2 py-1 text-white bg-violet-400 rounded-full file-input-button" style="color: #fff; background-color: #0d4dab; padding-top:0.25rem;padding-bottom:0.25rem;padding-left: 0.5rem;padding-right: 0.5rem;border-radius: 9999px;border:none">
                                select a file
                              </button>
                              from your device
                            </div>
                            <p class="mt-12 text-gray-400 text-sm" style="font-size: 0.875rem;line-height: 1.25rem;margin-top: 3rem;margin-bottom: 0;color:#000;font-weight:500">
                              JPG, PNG, PDF, DOC or MP4 only, maximum file size 70Mb
                            </p>
                            <input type="file" class="file-input" hidden name="image_data"/>
                          </div>
                        </div>
                        <p class="text-red-700 text-sm hidden" style="color: red" id="filesize-error">
                          The file size should be less than 70mb
                        </p>
                        <p class="text-red-700 text-sm hidden" style="color: red" id="filetype-error">
                          The file should be an image or video only
                        </p>
                        <p class="hidden text-xl text-red-600 text-center bg-pink-200 mt-6 p-3 rounded-lg" id="input-empty-error" style="color: red"></p>
                        <ul id="document-images" class="mt-6 px-3 bg-slate-100" style="margin-top: 1.5rem;list-style:none;background-color: #c3dcff;padding-right:20px;"></ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group pull-right" style="margin-bottom:0">
                  <button type="submit" class="btn btn-primary">Submit</a>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </section>
      </section>
    </section>
  </div>
  @endsection
  @section('ownjs')
  <!-- Drag and Drop Script -->
  <script>
    $(document).on('change', '#mark_cat', function(){
      var value = $("#mark_cat option:selected").val();
      if(value == 'Tips and Tricks'){
        $('.text_description').css('display', 'block')
      }else{
        $('.text_description').css('display', 'none')
      }
    })
    const dropArea = document.querySelector(".drag-area"),
    dragFile = dropArea.querySelector(".drag-file"),
    button = dropArea.querySelector(".file-input-button"),
    input = dropArea.querySelector(".file-input");
    let documentImages = document.querySelector("#document-images");
    let documentFileObj = {
      fileName: []
    };
    const validationInputs = (container, dataObject) => {
      const errorMessage = container.querySelector("#input-empty-error");
      const emptyFields = [];
      for (const key in dataObject) {
        if (dataObject[key].length <= 0) {
          emptyFields.push(key.toUpperCase());
        }
      }
      errorMessage.textContent = `Please fill ${emptyFields.join()} fields!!`;
      errorMessage.classList.remove("hidden");
      setTimeout(() => {
        errorMessage.classList.add("hidden");
      }, 2000);
    };
    button.onclick = (e) => {
      e.preventDefault();
      input.click();
    };
    input.addEventListener("change", function (e) {
      const target = e.target;
      setttingFileValue(target);
    });
    documentImages.addEventListener("click", (e) => {
      const target = e.target;
      const deleteFileButton = target.closest(".delete-document");
      const documentsWrapper = target.closest("#document-images");
      const documentToDelete = target.closest(".document-file");
      const documentName = documentToDelete.firstElementChild.children[1].innerText;
      if (deleteFileButton === null) return;
      const index = documentFileObj["fileName"].find((x) => x === documentName);
      documentFileObj["fileName"].splice(index, 1);
      documentsWrapper.removeChild(documentToDelete);
    });
    const fileTypeLogo = (fileType) => {
      if (fileType === "jpg" || fileType === "jpeg" || fileType === "png") {
        return "text-violet fa fa-image";
      }else if(fileType === "mp4"){
        return "fas fa-file-video";
      } 
      else {
        return "text-red fa fa-file-pdf";
      }
    };
    dropArea.addEventListener("dragover", (event) => {
event.preventDefault(); // preventing From Default Behaviour
dropArea.classList.add("active");
dragFile.textContent = "Release to Upload File";
});
    dropArea.addEventListener("dragleave", () => {
      dropArea.classList.remove("active");
      dragFile.textContent = "Drag files here to upload";
    });
    dropArea.addEventListener("drop", (e) => {
      e.preventDefault();
      const target = e.dataTransfer;
      dropArea.classList.remove("active");
      dragFile.textContent = "Drag files here to upload";
      setttingFileValue(target);
    });
    const setttingFileValue = (target) => {
      const fileName = target.files[0].name;
      const fileSize = target.files[0].size;
      const fileType = target.files[0].type.split("/").pop(); 
      let filesizeErrorMessage = document.getElementById("filesize-error");
      let filetypeErrorMessage = document.getElementById("filetype-error");
      let sizeInMB = Number.parseFloat(fileSize / (1024 * 1024)).toFixed(2);
      if (sizeInMB > 70) {
        filesizeErrorMessage.classList.remove("hidden");
      } else {
        filesizeErrorMessage.classList.add("hidden");
        let newDocument = document.createElement("li");
        newDocument.setAttribute(
          "class",
          "document-file"
          );
        newDocument.innerHTML = `
        <p style="white-space:nowrap; text-overflow:ellipsis;overflow:hidden; width:10rem;"><i class="fa-solid text-xl mr-5 ${fileTypeLogo(
          fileType
          )}"></i> 
        <span>${fileName}<span></p>
        <p>${fileType}</p>
        <p>${sizeInMB}mb</p>
        <p>Uploaded</p>
        <button class="delete-document" style="background-color: transparent;border:none"><i class="fas fa-trash"></i></button>
        `;
        documentImages.append(newDocument);
        documentFileObj["fileName"].push(fileName);
      }
    };
  </script>
  @endsection
<!-- Code Finalize -->