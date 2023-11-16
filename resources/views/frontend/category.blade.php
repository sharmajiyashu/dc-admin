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

        .category-selectbg li {
    display: inline-block;
    width: 32%;
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

    <section class="category-pagewp">
        <div class="container">
            <div class="category-pagesec">
                <div class="category-selectbg">
                    <ul>
                        <li style="">
                            <div class="input-group input-group-lg">
                                <input type="text" id="productSearch" class="form-control" onchange="searchProduct()"  placeholder="Search">
                              </div>
                        </li>

                        
                        <li style="">
                            <div class="dropdown">
                                <select class="form-select form-select-lg mb-3" aria-label="Large select example" onchange="changeCategory(this.value)">
                                    <option value="0">All</option>
                                    @foreach ($categories as $item)
                                    <a href=""></a>
                                        <option style="font-size: 14px;" {{ (isset($category->id) && $category->id == $item->id) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                        </li>

                        <script>
                            function changeCategory(value){
                                var userId = {{ $user->id}};
                                // Construct the URL using the route function
                                var url = `{{ route('view_store_categories', [':value', ':userId']) }}`
                                    .replace(':value', value)
                                    .replace(':userId', userId);
                                
                                // Redirect to the generated URL
                                window.location.href = url;
                            }
                        </script>

                        <li style="">
                            <select class="form-select form-select-lg mb-3" aria-label="Large select example">
                                <option style="font-size: 14px;" selected>Newest</option>
                                <option style="font-size: 14px;" value="1">Lowest Price</option>
                                <option style="font-size: 14px;" value="2">Highest Price</option>
                                <option style="font-size: 14px;" value="3">Oldest</option>
                                <option style="font-size: 14px;" value="4">Newest</option>
                            </select>
                        </li>
                    </ul>
                </div>
                <div class="row">


                    @foreach ($products as $item)

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <a href="{{ route('view_store_product_detail',$item->id) }}">
                                <div class="category-pagbox">
                                    <div class="imgbggb">
                                        <div class="imgbggb">
                                        <img src="{{$item->image }}" alt="">
                                    </div>
                                    </div>
                                    <div class="category-pagboxcon">
                                        <ul>
                                            <li>
                                                <b>{{ $item->name }}</b>
                                            </li>
                                            <li class="rightbg">
                                                RS {{ $item->sp }}
                                            </li>
                                        </ul>
                                        <ul>
                                            <li>
                                                1 BOX = {{ $item->packing_quantity }}
                                            </li>
                                            <li  class="rightbg">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-success" style="padding: 2px 8px;">
                                                    + Add
                                                </button>
                                                
                                                <!-- Modal -->
                                                
                                                
                                            </li>
                                        </ul>
                                    </div>
                                    
                                </div>
                            </a>
                        </div>
                    @endforeach
                    
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="{{ asset('public/frontend/js/code.jquery.com_jquery-3.7.1.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        // function searchProduct(){
            const productSearchInput = document.getElementById('productSearch');

            // Reference to the product containers
            const productContainers = document.querySelectorAll('.col-lg-3');

            // Add an event listener to the search input
            productSearchInput.addEventListener('input', function() {
                const searchText = productSearchInput.value.toLowerCase();

                // Loop through each product container
                productContainers.forEach(container => {
                    const productName = container.querySelector('b').textContent.toLowerCase();

                    // Check if the product name contains the search text
                    if (productName.includes(searchText)) {
                        container.style.display = 'block'; // Show the product container
                    } else {
                        container.style.display = 'none'; // Hide the product container
                    }
                });
            });

        // }
    </script>
   

    
    
</body>
</html>