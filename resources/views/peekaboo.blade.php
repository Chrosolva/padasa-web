<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>A Spooky Birthday</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="{{url('assets/assets2/style.css')}}">

        <!-- script -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
    </head>
    
    <body>
    
    <header>
        <h2 class="logo">Peek-a-Boo</h2>
        <nav class="navigation">
            <a href="#" class="active">Home</a>
            <a href="#">About</a>
            <a href="#">Middle</a>
            <a href="#">End</a>
        </nav>
    </header>


    <section class="parralax">
        
        <img src="{{url('assets/assets2/castleandbat.png')}}" id="castleandbat">
        <img src="{{url('assets/assets2/landandtree.png')}}" id="landandtree">
        <img src="{{url('assets/assets2/pumpkingate.png')}}" id="pumpkingate">
        <h2 id="text">A Spooky Birthday</h2>
        <br>
        <h4 id="text2">Just For You</h4>
        <a href="#sec" id="btnn">Explore</a>
    </section>

    <div class="sec" id="sec">
        <h2 id="text4">Time Flies So Fast </h2>
        <h4 id="text5">And Today is Your Special Day, Let's See Our Previous Memory</h4>

        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="8"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="9"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="10"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="11"></li>
              
            </ol>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img id="pict1" class="d-block img-fluid" src="{{url('assets/assets2/kellen 2010.jpg')}}" alt="First slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid " src="{{url('assets/assets2/kellen2012.jpg')}}" alt="kellen2012.JPG">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/assets2/kellen2013.jpg')}}" alt="kellen2013">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/assets2/kellen2014.jpg')}}" alt="kellen2014">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/assets2/kelen2015.jpg')}}" alt="kelen2015 slide">
              </div>
              <div class="carousel-item">
                <img id="pict1" class="d-block img-fluid" src="{{url('assets/assets2/kellen2017.jpg')}}" alt="kellen2017 slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid " src="{{url('assets/assets2/kelen2015.jpg')}}" alt="kellen2018 slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/assets2/kellen2018.jpg')}}" alt="kellen20182 slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/assets2/kellen2019.jpg')}}" alt="kellen2019 slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/assets2/kellen20192.jpg')}}" alt="kellen20192 slide">
              </div>
              <div class="carousel-item">
                <img class="d-block img-fluid" src="{{url('assets/assets2/kellen2020.jpg')}}" alt="kellen2020 slide">
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        <br><br><br>
        <h4 id="text6">2010 to 2015, you were still so little back then.</h4>
        <br>
        <h4 id="text7">2017-2023, You gained height and almost as tall as me now</h4>
    </div>
    <section>

        <div id="sec2" class="sec2">
            <div class="row">
                <div class="col-sm">
                  <img src="{{url('assets/assets2/kellen2023 0.JPG')}}" alt="kellen2023 0.JPG" class="img-fluid">
                </div>
                <div class="col-sm">
                  <img src="{{url('assets/assets2/kellen2023 crop.jpg')}}" alt="kellen2023 crop.jpg" class="img-fluid">
                </div>
                <div class="col-sm">
                  <img src="{{url('assets/assets2/kellen2023 2 crop.jpg')}}" alt="kellen2023 2 crop.jpg" class="img-fluid">
                </div>
            </div>

            <h2 id="textB">Happy Sweet Seventeen !!!</h2>
        </div>    
    </section>
    
   
    
    

    <script>
        let castleandbat = document.getElementById('castleandbat');
        let landandtree = document.getElementById('landandtree');
        let pumpkingate = document.getElementById('pumpkingate');
        let spooky = document.getElementById('text');
        let just4u = document.getElementById('text2');
        let btnn = document.getElementById('btnn');
        let header = document.querySelector('header');
        let text4 = document.getElementById('text4');
        let text5 = document.getElementById('text5');
        let text6 = document.getElementById('text6');
        let text7 = document.getElementById('text7');

        window.addEventListener('scroll', function(){
            let value = window.scrollY;
            castleandbat.style.top = value * 0.95 + 'px';
            landandtree.style.left = value * 0.45 + 'px';
            spooky.style.marginLeft = value * 1.5 + 'px';
            
            just4u.style.fontSize = value * 0.25 + 'px';
            just4u.style.marginRight = value * 0.5 + 'px';
            
            header.style.top = value * 0.05 + 'px';

            text4.style.marginLeft = value * 0.60 + 'px';
            text5.style.marginRight = value * 0.35 + 'px';
            text6.style.marginLeft = value * 0.45 + 'px';
            text7.style.marginRight = value * 0.30 + 'px';
            
            if($(window).scrollTop() < 1500){

            //Then change the elements position to fixed:
                spooky.style.marginTop = value * 2.5 + 'px';
                btnn.style.marginTop = value * 1.75 + 'px';
                pumpkingate.style.marginTop = value * 0.85 + 'px';
            }

            
        })
    </script>
</body>
</html>
