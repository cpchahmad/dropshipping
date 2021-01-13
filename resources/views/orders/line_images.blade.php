@if(count($images) > 0)
    @foreach($images as $image)

        <div class="col-md-4">
            <a href='{{ $image->src }}'  class="fancybox" rel="group">
                <img src="{{ $image->src }}" alt='No Image Found' class="img-fluid hover-img" style="width: 100%; height: auto; z-index: 9999;">
            </a>
        </div>
    @endforeach
@else
    <p class="pl-2">No images</p>
@endif


<script>
    $(document).ready(function () {
        $(".fancybox").fancybox();
    });
</script>

