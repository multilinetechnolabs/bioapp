<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  @include('partials.shared.meta')
  @include('partials.shared.link_fonts')

  <!-- Styles -->
  <link href="{{ asset('css/app/landing_page.css') }}" rel="stylesheet">

  <title>{{ env('APP_TITLE') }} | An App for Therapist and Practitioners of Biomagnetism</title>
</head>

<body>
  <header class="top_page">
    <div class="col-md-12 text-center m-0" id="top_bar">
      <div class="top-header">
        <div class="row">
          <div class="col-md-1">
            <div class="logo">
              <a href=""><img src="{{ asset('images/landing_page/+- _Asset 1.png') }}" class="img-responsive"></a>
            </div>
          </div>
          <div class="col-md-10 text-white" id="title">
            <h1>ANEW AVENUE</h1>
            <h1>BIOGMANETISM.APP</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="container col-md-10 text-center ">
        <div class="balance">
          <img src="{{ asset('images/landing_page/BalanceMan_Asset 5.png') }}" class="img-responsive">
        </div>
      </div>
    </div>
  </header>

  <div class="container-fluid slider">
    <!-- Swiper -->
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="image1">
            <img src="{{ asset('images/landing_page/Image1_Asset 6.png') }}">
          </div>
        </div>
        <div class="swiper-slide">
          <div class="image1">
            <img src="{{ asset('images/landing_page/Image2.png') }}">
          </div>
        </div>
        <div class="swiper-slide">
          <div class="image1">
            <img src="{{ asset('images/landing_page/Image3_Asset 11.png') }}">
          </div>
        </div>
      </div>
      <!-- Add Pagination -->
      <div class="swiper-pagination"></div>
    </div>
  </div>
  <!-- /Swiper -->

  <!-- 3rd Section -->
  <div class="container-fluid text-center m-0 p-0">
    <div class="third text-center text-white">
      <div class="row">
        <div class="col-md-12 text-center text-white">
          <h4>The most empowered, integrated and consolidated</h4>
          <h4>biogmanetism app available.</h4>
        </div>
        <div class="col-md-12 text-center" id="join">
          <a href="#"><img src="{{ asset('images/landing_page/Join_Asset 7.png') }}" title="join" class="img-responsive" /></a>
        </div>
        <div class="col-md-12" id="bg_join">
          <img src="{{ asset('images/landing_page/Design1_Asset 8.png') }}" class="img-responsive" />
        </div>
        <div class="col-md-12" id="bg_bar">
          <img src="{{ asset('images/landing_page/Box_Asset 10.png') }}" class="img-responsive" />
        </div>
        <div class="col-md-4 vid1">
          <a href="#"><img src="{{ asset('images/landing_page/vid1.png') }}" class="img-responsive" /></a>
        </div>
        <div class="col-md-4 vid2">
          <a href="#"><img src="{{ asset('images/landing_page/vid2.png') }}" class="img-responsive" /></a>
        </div>
        <div class="col-md-4 vid3">
          <a href="#"><img src="{{ asset('images/landing_page/vid3.png') }}" class="img-responsive" /></a>
        </div>
      </div>
    </div>
  </div>
  <!-- /3rd section -->

  <!-- 4th section -->
  <div class="container-fluid text-center m-0 p-0">
    <div class="fourth text-center text-white">
      <img src="{{ asset('images/landing_page/Image2.png') }}">
    </div>
  </div>
  <!-- /4th section -->

  <!-- 5th section -->
  <div class="container-fluid text-center m-0 p-0">
    <div class="fifth text-center text-white">
      <img src="{{ asset('images/landing_page/Image3_Asset 11.png') }}">
    </div>
  </div>
  <div class="container-fluid text-center m-0 p-0">
    <div class="col-md-12 text-center" id="caption">
      <h2>Discover Anew Avenue Biogmanetism</h2>
    </div>
  </div>
  <!-- /5th section -->

  <!-- 6th Section -->
  <div class="container-fluid text-center m-0 p-0" id="introducing">
    <div class="row">
      <div class="sixth col-md-6 text-white">
        <h1>INTRODUCING</h1>
        <h1>ANEW AVENUE</h1>
        <h1>BIOGMANETISM APP</h1>
        <br>
        <p class="hereis">Here is what the app features:</p>
        <div class="row">
          <div class="col-md-12 list">

            <p>Biogmanetism Scan</p>

            <p>Chakra Scan</p>

            <p>Client Data Cache</p>

            <p>Bio-connect</p>
          </div>
          <div class="col-md-12 list bot">
            <h3>THIS APP IS DESIGNED FOR</h3>
            <h3>HEALERS AS WELL AS PATIENTS.</h3>
          </div>

        </div>
      </div>
      <div class="sixth1 col-md-6">
        <img src="{{ asset('images/landing_page/MobileImage_Asset 13.png') }}">
      </div>
    </div>
  </div>
  <!-- /6th section -->

  <!-- 7th section -->
  <div class="container-fluid m-0 p-0" id="seventh">
    <div class="row">
      <div class="man_blur col-md-6">
        <img src="{{ asset('images/landing_page/man_blur.jpg') }}">
      </div>
      <div class="side col-md-6">
        <div class="row">
          <div class="col-sm-12 text-white">
            <br><br>
            <h4>Client database encompassing client information, medical history, business records</h4>
          </div>
          <div class="col-sm-12 text-white">
            <br>
            <h4>Access to more than 842 updated bio pairs and 270 chakra pairs</h4>
          </div>
          <div class="col-sm-12 text-white">
            <br>
            <h4>Details regarding medical symptoms, their origin, their effects and alternatives with</h4>
          </div>
          <div class="col-sm-12 text-white">
            <br>
            <h4>Complete 3D body scan models points of disease</h4>
          </div>
          <div class="col-sm-12 text-white">
            <br>
            <h4>A comprehensive guide to Biogmanetism body scans</h4>
          </div>
          <div class="col-sm-12 text-white">
            <br>
            <h4>Media center featuring calming tunes, with the option to create your playlist</h4>
          </div>
          <div class="col-sm-12 text-white">
            <br>
            <h4>And much more.....</h4>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- /7th sectivon -->

  <!-- 8th section -->
  <div class="eighth container-fluid text-left">
    <div class="background">
      <p>
        Today's advanced world and it's a toxic lifestyle, are giving birth to countless ailments, which are either identified at the earliest or go unidentified until some major loss occurs.
        <br>
        Although, scientists are constantly studying new ways yo subsiding the disease a huge need for a treatment alternative.
        <br>
        In such cases, Biomagnetism therapy steps in as a savior for everyone. Here is why this treatment shall be considered seriously.
      </p>
      <br>
      <br>
      <h4>The inefficacy of reductionist treatments</h4>
      <p>
        For long, scientists and doctors have depended on the reductions approach to treat different ailments. Despite its assurance of treatment, the reductionist approach has one major pitfall, which makes it hard to identify the actual cause of any disease.
        <br>
        For instance, cancer cells are hard to localize as they are mostly in a metastatic state. The choice of picking a targeted therapy of chemotherapy is way too expensive to be done on resting basis. Localization of the source of any illness is the first step towards sustainable healing from any disease. Biomagnetism helps in so many ways when the question of healing is under consideration.
      </p>
      <br>
      <br>
      <h4>The Solution - Biomagnetic Therapy</h4>
      <p>As the name indicates, the therapy involves two oppositely charged magnets which are placed on the body. To understand Biomagnetic therapy, first, we need to establish the fact that a healthy body has a lowered pH of around 7.35 to 7.45. Any viral, fungal, pathogenic, or bacterial invasion is bound to topple the optional pH.
        <br>
        In most cases, the body tends to become more acidic. Biomagnetism therapy not only restores the pH balance of the body but also help in subsiding disease fields from the magnets and cells of the body to achieve desired effects. One of the major advantages of biomagnetism therapy is that it offers a detailed etiology, cause, and location of any ailment in your body, without disturbing the natural course of events. Once the source and cause are identified, it becomes more and easier to combat any disease.
      </p>
    </div>
  </div>
  <!-- /8th section -->

  <!-- 9th section -->
  <div class="row">
    <div class="ninth col-md-12">
      <h2>What does it cure?</h2>
      <p>
        Biomagnetism therapy works at a subatomic level to subside the domino effect of free radicals as well as to restore the election imbalances inside the body. At present, the following diseases are out of the many, which is being treated by using Biomagnetism therapy:
      </p>
      <ul>
        <li>Immune diseases</li>
        <li>Different types of cysts</li>
        <li>Neurological disorders</li>
        <li>Dermatological disorders</li>
        <li>Musculoskeletal ailments</li>
        <li>Systematic malfunctions</li>
        <li>Respiratory diseases</li>
        <li>ENT diseases</li>
        <li>Cardiovascular ailments</li>
        <li>Retinal issues</li>
        <li>Hematological disorders</li>
        <li>Haptic issues</li>
        <li>Hypertension</li>
        <li>Renal problems</li>
        <li>Gynecological problems</li>
        <li>Gastrointestinal infections</li>
        <li>Genitourinary infections</li>
        <li>Emotional disorders</li>
        <li>Sexually transmitted diseases</li>
        <li>Cancer</li>
        <li>And much more ... </li>
      </ul>
      <p><b>
        Non Medical Devise Disclaimer:
        "Not intented to treat disease, support or sustain human life. or to prevent impairment of human health"
      </b></p>
    </div>
  </div>
  <!-- /9th section -->

  <!-- footer -->
  <footer>
    <div class="footer container-fluid text-center">
      <div class="row">
        <div class="col-md-12 text-right text-white" id="stay_connected">
          <p>Stay connected with us</p>
          <a href="javascript:;"><img src="{{ asset('images/landing_page/Fb_Asset 19.png') }}"></a>
          <a href="javascript:;"><img src="{{ asset('images/landing_page/Tw_Asset 20.png') }}"></a>
          <a href="javascript:;"><img src="{{ asset('images/landing_page/You_Asset 21.png') }}"></a>
        </div>
      </div>

      <div class="row">
        <div class="col-md-2 ml-auto logo_left">
          <img src="{{ asset('images/landing_page/aNewLogo_Asset 18.png') }}" class="img-responsive">
        </div>
        <div class="col-md-8 mx-auto text-white" id="footer_logo">
          <img src="{{ asset('images/landing_page/+- _Asset 1.png') }}" class="img-responsive">
          <h3>Anew Avenue Biogmanetism.app</h3>
        </div>
        <!-- scroll to top -->
        <div class="col-md-2 my-auto logo_right">
          <a href="#top" class="to-top"><img src="{{ asset('images/landing_page/UpButton_Asset 22.png') }}" class="img-responsive"></a>
        </div>
        <!-- scroll to top -->
      </div>
    </div>
  </footer>
  <!-- /footer -->
  @include('partials.shared.foot')
  <script>
    $(document).ready(function() {
      $('a[href="#top"]').on('click', function(ev) {
        event.preventDefault();

        $('body,html').animate({
          scrollTop: 0
        }, 500);
      });

      // Swiper
      swiper_options = {
        effect: 'coverflow',
        direction: 'horizontal',
        loop: true,
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 'auto',
        pagination: {
          el: '.swiper-pagination',
        },
        coverflowEffect: {
          rotate: 0,
          stretch: 0,
          depth: 100,
          modifier: 4,
          slideShadows: true,
        },
      }
      var swiper = new Swiper('.swiper-container', swiper_options);
    });
  </script>
</body>

</html>