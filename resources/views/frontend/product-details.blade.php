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
        #myInput {
  box-sizing: border-box;
  background-image: url('searchicon.png');
  background-position: 14px 12px;
  background-repeat: no-repeat;
  font-size: 16px;
  padding: 14px 20px 12px 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  border-bottom: 1px solid #c4c4c4;
}
        
        .dropdown-content {
          position: absolute;
          background-color: #f6f6f6;
          min-width: 230px;
          overflow: auto;
          border: 1px solid #ddd;
          z-index: 1;
          display: none;
        }
        
        .dropdown-content a {
          color: black;
          padding: 12px 16px;
          text-decoration: none;
          display: block;
        }
        </style>
</head>
<body>

    <header>
        <div class="header-wp">
            
            <div class="container header-homeicon">
                <a href="{{ route('view_store',$user->store_code) }}" style="font-weight: 600;"><img src="{{ asset('public/frontend/images/home.png') }}" alt="" width="24px"> {{ $user->store_name }}</a>
            </div>
        </div>
    </header>

    <section class="product-details">
        <div class="container">
            <div class="product-detailssec">
                <div class="row gbbgrow">
                    <div class="col-lg-7 col-md-6 col-sm-12">
                        <div class="product-detailleft">
                            <div class="owl-carousel">
                                @foreach ($product->original_images as $item)
                                <div class="item">
                                    <img src="{{ $item }}" alt="">
                                </div>
                                @endforeach
                                {{-- <div class="item">
                                    <img src="http://localhost/dc/public/images/products/169683974227-images(2).jpeg" alt="">
                                </div>
                                <div class="item">
                                    <img src="http://localhost/dc/public/images/products/169683974227-images(2).jpeg" alt="">
                                </div>
                                <div class="item">
                                    <img src="http://localhost/dc/public/images/products/169683974227-images(2).jpeg" alt="">
                                </div>
                                <div class="item">
                                    <img src="http://localhost/dc/public/images/products/169683974227-images(2).jpeg" alt="">
                                </div>
                                <div  class="item">
                                    <img src="http://localhost/dc/public/images/products/169683974227-images(2).jpeg" alt="">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <div class="product-detailright">
                            <div class="row product-detailrightbgrow">
                                <div class="col-md-6">
                                    <b>
                                       
                                        
                                        RS 
                                        @if ($product->mrp > $product->sp)
                                        <span style="
                                        font-size: 90%;color: #bdbdbd
                                    "><del>{{ $product->mrp }}</del></span>     
                                        @endif
                                        
                                        
                                        {{ $product->sp }}</b>
                                    <br>
                                    <br>
                                    <span style="font-size: 14px;">{{ $product->name }}</span>
                                </div>
                                <div class="col-md-6">
                                    <div class="product-detailright-iconstar">
                                        <ul>
                                            <li>
                                                <img src="{{ asset('public/frontend/images/starIcon.png')}}" alt="">
                                            </li>
                                            <li>
                                                <img src="{{ asset('public/frontend/images/starIcon.png')}}" alt="">
                                            </li>
                                            <li>
                                                <img src="{{ asset('public/frontend/images/starIcon.png')}}" alt="">
                                            </li>
                                            <li>
                                                <img src="{{ asset('public/frontend/images/starIcon.png')}}" alt="">
                                            </li>
                                            <li>
                                                <img src="{{ asset('public/frontend/images/starIcon.png')}}" alt="">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="nextItombg row">
                                <ul class="incdecbg">
                                    <li>
                                        <button onclick="decbg()" class="btn btn-danger">-</button>
                                    </li>
                                    <li>
                                        <button class="btn btn-outline-danger" id="updatvalue">1</button>
                                    </li>
                                    <li>
                                        <button onclick="incbg()" class="btn btn-danger">+</button>
                                    </li>
                                    <br>
                                    <br>
                                    
                                    {{-- <br> --}}
                                </ul>
                                <div class="col-md-4 col-sm-2 nextItombg-left">
                                    <img src="{{ asset('public/frontend/images/rightbggb.svg')}}" alt="">
                                </div>
                                <div class="col-md-4 col-sm-8 nextItombg-center">
                                    <a href="https://play.google.com/store/apps/details?id=com.jwelerydukancustomer" class="btn btn-danger">
                                      
                                        <img src="{{ asset('public/frontend/images/cartIcon.svg')}}" alt=""> Add to Cart 
                                    </a>
                                </div>
                                <div class="col-md-4 col-sm-2 nextItombg-right">
                                    <img src="{{ asset('public/frontend/images/nextbggb.svg')}}" alt="">
                                </div>
                            </div>
                            <script>
                                var valueDi =1;
                                function incbg(){
                                    if (valueDi < 50){
                                        valueDi++
                                        document.getElementById('updatvalue').innerHTML = valueDi;
                                    }
                                }
                                function decbg(){
                                    if (valueDi > 1){
                                        valueDi--
                                        document.getElementById('updatvalue').innerHTML = valueDi;
                                    }
                                }
                            </script>


                        </div>
                    </div>
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
                autoplayTimeout: 1500, // Auto-play interval in milliseconds (3 seconds in this example)
                autoplayHoverPause: true, // Pause autoplay on hover
                dots: true, // Show navigation dots
                responsive: {
                    // Define responsive breakpoints
                    0: {
                        items: 1 // Display one item on screens less than 600px wide
                    },
                    768: {
                        items: 1 // Display two items on screens 600px or wider
                    },
                    992: {
                        items: 1 // Display three items on screens 992px or wider
                    },
                    1200: {
                        items: 1 // Display four items on screens 1200px or wider
                    }
                }
            });
        });
            </script>
   
    
</body>
</html>