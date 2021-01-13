{{--@dd(count($images))--}}
@if(count($images) > 0)
    @foreach($images as $image)
        @foreach($image as $img)

            <div class="col-md-4">
                <a href='{{ $img['src'] }}'  class="fancybox" rel="group">
                    <img src="{{ $img['src'] }}" alt='No Image Found' class="img-fluid hover-img" style="width: 100%; height: auto; z-index: 9999;">
                </a>
            </div>
        @endforeach

    @endforeach
@else
    <p class="pl-2">No images</p>
@endif


<script>
    $(document).ready(function () {
        $(".fancybox").fancybox();
    });
</script>

