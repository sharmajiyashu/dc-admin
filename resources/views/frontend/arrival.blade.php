<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">

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
            <div class="container">
                <a href="#" style="font-weight: 600;">YASHUVANASI</a>
                <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
            </div>
        </div>
    </header>

    <section class="category-pagewp">
        <div class="container">
            <div class="category-pagesec">
                <div class="category-selectbg">
                    <ul>
                        <li style="display: inline-block;">
                            <div class="input-group input-group-lg">
                                <!-- <span class="input-group-text" id="inputGroup-sizing-lg">Large</span> -->
                                <input style="  box-sizing: border-box;
                                background-image: url('searchicon.png');
                                background-position: 14px 12px;
                                background-repeat: no-repeat;
                                font-size: 16px;
                                padding: 14px 20px 12px 20px;
                                border: 1px solid #ddd;
                                border-radius: 10px;
                                border-bottom: 1px solid #c4c4c4;" type="text" class="form-control" placeholder="Search" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
                              </div>
                        </li>
                        <li style="display: inline-block;">
                            <div class="dropdown">
                                <input type="text" placeholder="Category Search.." id="myInput" onfocus="showOptions()" onkeyup="filterFunction()">
                                <div id="myDropdown" class="dropdown-content">
                                  <a href="#about">About</a>
                                  <a href="#base">Base</a>
                                  <a href="#blog">Blog</a>
                                  <a href="#contact">Contact</a>
                                  <a href="#custom">Custom</a>
                                  <a href="#support">Support</a>
                                  <a href="#tools">Tools</a>
                                </div>
                              </div>
                        </li>
                        <li style="display: inline-block;">
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <a href="#">
                            <div class="category-pagbox">
                                <div class="imgbggb">
                                    <img src="images/product-1.png" alt="">
                                </div>
                                <div class="category-pagboxcon">
                                    <ul>
                                        <li>
                                            <b>B100</b>
                                        </li>
                                        <li class="rightbg">
                                            RS 50
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>
                                            1 BOX = 1
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
                </div>
            </div>
        </div>
    </section>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="js/code.jquery.com_jquery-3.7.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    
    </script>

    <script>
        function showOptions() {
          var div = document.getElementById("myDropdown");
          div.style.display = "block";
        }
        
        function filterFunction() {
          var input, filter, ul, li, a, i;
          input = document.getElementById("myInput");
          filter = input.value.toUpperCase();
          div = document.getElementById("myDropdown");
          a = div.getElementsByTagName("a");
          
          for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              a[i].style.display = "block"; // Show relevant options
            } else {
              a[i].style.display = "none";  // Hide irrelevant options
            }
          }
        }
        
        // Add an event listener to close the dropdown when clicking outside
        document.addEventListener("click", function(e) {
          var dropdown = document.getElementById("myDropdown");
          var input = document.getElementById("myInput");
          
          if (e.target !== dropdown && e.target !== input) {
            dropdown.style.display = "none";
          }
        });
        </script>
    
</body>
</html>