<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('public/frontend/css/responsive.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <style>

        

    </style>
</head>
<body>

    <header>
        <div class="header-wp">
            <div class="container header-homeicon">
                <a href="{{ route('view_store',$user->store_code) }}"><img src="{{ asset('public/frontend/images/home.png') }}" alt="" width="24px"></a>
                <a href="#" style="font-weight: 600;">{{ $user->store_name }}</a>
                <form class="d-flex" role="search">
                    
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    {{-- <button class="btn btn-outline-success" type="submit">Search</button> --}}
                </form>
            </div>
        </div>
    </header>

    <section class="bannerwp">
        <div class="container">
            <a href="https://play.google.com/store/apps/details?id=com.jwelerydukancustomer" target="_blank">
                <img src="{{ asset('public/frontend/images/dc_banner.png')}}" style="width: 100%;" alt="Image 1">
            </a>
        </div>

        {{-- <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="{{ asset('public/frontend/images/banner1.jpeg')}}" style="width: 100%;" alt="Image 1">
              </div>
              <div class="carousel-item">
                <img src="{{ asset('public/frontend/images/banner1.jpeg')}}" style="width: 100%;" alt="Image 1">
              </div>
              <div class="carousel-item">
                <img src="{{ asset('public/frontend/images/banner1.jpeg')}}" style="width: 100%;" alt="Image 1">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div> --}}
    </section>

    <section class="arrival-wp">
        <div class="container">
            <div class="arrival-sec">
                <div class="arrival-heading">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>
                                New Arrival
                            </h3>
                        </div>
                        <div class="col-md-6 heading-arrivalright">
                            <a href="{{ route('view_store_categories',[0,$user->id]) }}">View All</a>
                        </div>
                    </div>
                </div>
                <div class="owl-carousel">
                    @foreach ($products as $item)
                        <div class="item boxcmnbg">
                            <a href="{{ route('view_store_product_detail',$item->id) }}">
                                <img src="{{$item->image }}" alt="Image 1">
                                <div class="boxcontantbg-cmn">
                                    <b>RS {{ $item->sp }}</b><br>
                                    <span>{{ $item->name }}</span>
                                </div>
                            </a>
                        </div>    
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="category-wp">
        <div class="container">
            <div class="category-sec">
                <h3>Category</h3>
                <div class="row">
                    @foreach ($categories as $item)
                        <a href="{{ route('view_store_categories',[$item->id,$user->id]) }}"  class="col-lg-2 col-md-4 col-sm-6 categorybg-cmn">
                            <img src="{{ asset('public/images/categories/'.$item->image) }}" alt="">
                            <p>{{ $item->title }}</p>
                        </a>    
                    @endforeach
                </div>
            </div>
        </div>
    </section>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="{{ asset('public/frontend/js/code.jquery.com_jquery-3.7.1.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>



// 

$(document).ready(function(){
    $(".owl-carousel").owlCarousel({
        items: 4, // Number of items to display at a time
        loop: true, // Enable infinite loop
        autoplay: true, // Auto-play the carousel
        autoplayTimeout: 1000, // Auto-play interval in milliseconds (3 seconds in this example)
        autoplayHoverPause: true, // Pause autoplay on hover
        dots: true, // Show navigation dots
        responsive: {
            // Define responsive breakpoints
            0: {
                items: 2.2 // Display one item on screens less than 600px wide
            },
            768: {
                items: 2 // Display two items on screens 600px or wider
            },
            992: {
                items: 3 // Display three items on screens 992px or wider
            },
            1200: {
                items: 4 // Display four items on screens 1200px or wider
            }
        }
    });
});
    </script>
    
</body>
</html>