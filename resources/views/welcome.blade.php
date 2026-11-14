<!DOCTYPE html>
<html lang="en">
<head>
    <title>Al Muntadhir Youth Forum – Empowering Young Minds</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<!-- top bar with forum contact -->
<div class="py-2 bg-primary">
    <div class="container">
        <div class="row no-gutters d-flex align-items-start align-items-center px-3 px-md-0">
            <div class="col-lg-12 d-block">
                <div class="row d-flex">
                    <div class="col-md-5 pr-4 d-flex topper align-items-center">
                        <div class="icon bg-fifth mr-2 d-flex justify-content-center align-items-center"><span class="icon-map"></span></div>
                        <span class="text">Al Muntadhir Hub, 22 Youth Avenue, Kampala</span>
                    </div>
                    <div class="col-md pr-4 d-flex topper align-items-center">
                        <div class="icon bg-secondary mr-2 d-flex justify-content-center align-items-center"><span class="icon-paper-plane"></span></div>
                        <span class="text">info@almuntadhiryouth.org</span>
                    </div>
                    <div class="col-md pr-4 d-flex topper align-items-center">
                        <div class="icon bg-tertiary mr-2 d-flex justify-content-center align-items-center"><span class="icon-phone2"></span></div>
                        <span class="text">+256 700 123 456</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ftco_navbar ftco-navbar-light" id="ftco-navbar">
    <div class="container d-flex align-items-center">
        <a class="navbar-brand" href="index.html">Al Muntadhir Youth</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
<!-- END nav -->

<!-- hero slider -->
<section class="home-slider owl-carousel">
    <div class="slider-item" style="background-image:url(images/bg_1.jpg);">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
                <div class="col-md-8 text-center ftco-animate">
                    <h1 class="mb-4">Al Muntadhir Youth Forum <span>Where young leaders are shaped</span></h1>
                    <p><a href="#" class="btn btn-secondary px-4 py-3 mt-3">Explore our programs</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="slider-item" style="background-image:url(images/bg_2.jpg);">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
                <div class="col-md-8 text-center ftco-animate">
                    <h1 class="mb-4">Skills · Dialogue · Action <span>For a brighter future</span></h1>
                    <p><a href="#" class="btn btn-secondary px-4 py-3 mt-3">Join the movement</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- core values / services -->
<section class="ftco-services ftco-no-pb">
    <div class="container-wrap">
        <div class="row no-gutters">
            <div class="col-md-3 d-flex services align-self-stretch pb-4 px-4 ftco-animate bg-primary">
                <div class="media block-6 d-block text-center">
                    <div class="icon d-flex justify-content-center align-items-center"><span class="flaticon-teacher"></span></div>
                    <div class="media-body p-2 mt-3">
                        <h3 class="heading">Experienced Mentors</h3>
                        <p>Youth and professionals guiding peers in leadership, career and life skills.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex services align-self-stretch pb-4 px-4 ftco-animate bg-tertiary">
                <div class="media block-6 d-block text-center">
                    <div class="icon d-flex justify-content-center align-items-center"><span class="flaticon-reading"></span></div>
                    <div class="media-body p-2 mt-3">
                        <h3 class="heading">Inclusive Dialogues</h3>
                        <p>Safe spaces for discussing identity, community and global issues.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex services align-self-stretch pb-4 px-4 ftco-animate bg-fifth">
                <div class="media block-6 d-block text-center">
                    <div class="icon d-flex justify-content-center align-items-center"><span class="flaticon-books"></span></div>
                    <div class="media-body p-2 mt-3">
                        <h3 class="heading">Resource Library</h3>
                        <p>Digital and physical materials on civic engagement, arts & entrepreneurship.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex services align-self-stretch pb-4 px-4 ftco-animate bg-quarternary">
                <div class="media block-6 d-block text-center">
                    <div class="icon d-flex justify-content-center align-items-center"><span class="flaticon-diploma"></span></div>
                    <div class="media-body p-2 mt-3">
                        <h3 class="heading">Youth Certification</h3>
                        <p>Recognizing volunteer hours, workshop completion and active participation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- about / welcome to forum -->
<section class="ftco-section ftco-no-pt ftc-no-pb">
    <div class="container">
        <div class="row">
            <div class="col-md-5 order-md-last wrap-about py-5 wrap-about bg-light">
                <div class="text px-4 ftco-animate">
                    <h2 class="mb-4">Welcome to Al Muntadhir Youth Forum</h2>
                    <p>We are a collective of young thinkers, creators and changemakers. Our story began in 2015 when a group of friends decided to turn conversation into action. Today we run mentorship circles, community projects and cultural events — all driven by youth, for youth.</p>
                    <p>From climate advocacy to skill-sharing workshops, our members shape the agenda. The forum is a platform where every voice matters, and every idea can grow. Whether you're 15 or 25, you’ll find a place here.</p>
                    <p><a href="#" class="btn btn-secondary px-4 py-3">Meet the team</a></p>
                </div>
            </div>
            <div class="col-md-7 wrap-about py-5 pr-md-4 ftco-animate">
                <h2 class="mb-4">What we offer our members</h2>
                <p>Al Muntadhir Youth Forum provides a springboard for personal and collective growth. Through peer learning, networking, and tangible projects, we equip young people with the tools to lead.</p>
                <div class="row mt-5">
                    <div class="col-lg-6">
                        <div class="services-2 d-flex">
                            <div class="icon mt-2 mr-3 d-flex justify-content-center align-items-center"><span class="flaticon-security"></span></div>
                            <div class="text">
                                <h3>Safe & brave space</h3>
                                <p>Respectful environment where difficult conversations become pathways to understanding.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="services-2 d-flex">
                            <div class="icon mt-2 mr-3 d-flex justify-content-center align-items-center"><span class="flaticon-reading"></span></div>
                            <div class="text">
                                <h3>Weekly forums</h3>
                                <p>Regular meetups — both online and in person — to discuss, plan and reflect.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="services-2 d-flex">
                            <div class="icon mt-2 mr-3 d-flex justify-content-center align-items-center"><span class="flaticon-diploma"></span></div>
                            <div class="text">
                                <h3>Leadership incubator</h3>
                                <p>From public speaking to project management, we grow together.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="services-2 d-flex">
                            <div class="icon mt-2 mr-3 d-flex justify-content-center align-items-center"><span class="flaticon-education"></span></div>
                            <div class="text">
                                <h3>Workshops & trainings</h3>
                                <p>Practical skills: digital storytelling, advocacy, financial literacy and more.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="services-2 d-flex">
                            <div class="icon mt-2 mr-3 d-flex justify-content-center align-items-center"><span class="flaticon-jigsaw"></span></div>
                            <div class="text">
                                <h3>Creative expression</h3>
                                <p>Art, poetry, spoken word — we celebrate youth culture in all its forms.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="services-2 d-flex">
                            <div class="icon mt-2 mr-3 d-flex justify-content-center align-items-center"><span class="flaticon-kids"></span></div>
                            <div class="text">
                                <h3>Community action</h3>
                                <p>Turn ideas into impact: local campaigns, outreach and volunteering.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- call to action: manners / community involvement -->
<section class="ftco-intro" style="background-image: url(images/bg_3.jpg);" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h2>Your ideas, your forum — let's build together</h2>
                <p class="mb-0">Al Muntadhir isn't just an organisation; it's a movement of young people who believe in dialogue, empathy and action. No matter your background, your perspective is needed here.</p>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <p class="mb-0"><a href="#" class="btn btn-secondary px-4 py-3">Become a member</a></p>
            </div>
        </div>
    </div>
</section>

<!-- mentors (formerly teachers) -->
<section class="ftco-section ftco-no-pb">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section ftco-animate">
                <h2 class="mb-4"><span>Core</span> Mentors & facilitators</h2>
                <p>Our team comes from diverse backgrounds — academia, activism, arts — all united by a passion for youth empowerment.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="staff">
                    <div class="img-wrap d-flex align-items-stretch">
                        <div class="img align-self-stretch" style="background-image: url(images/teacher-1.jpg);"></div>
                    </div>
                    <div class="text pt-3 text-center">
                        <h3>Zahra Namubiru</h3>
                        <span class="position mb-2">Lead facilitator, dialogue circles</span>
                        <div class="faded">
                            <p>Community psychologist with a passion for restorative practices and intergenerational dialogue.</p>
                            <ul class="ftco-social text-center">
                                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="staff">
                    <div class="img-wrap d-flex align-items-stretch">
                        <div class="img align-self-stretch" style="background-image: url(images/teacher-2.jpg);"></div>
                    </div>
                    <div class="text pt-3 text-center">
                        <h3>Ahmed Sekandi</h3>
                        <span class="position mb-2">Program coordinator, media & advocacy</span>
                        <div class="faded">
                            <p>Journalist and media trainer, supporting youth to tell their stories with impact.</p>
                            <ul class="ftco-social text-center">
                                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="staff">
                    <div class="img-wrap d-flex align-items-stretch">
                        <div class="img align-self-stretch" style="background-image: url(images/teacher-3.jpg);"></div>
                    </div>
                    <div class="text pt-3 text-center">
                        <h3>Lubowa Kaggwa</h3>
                        <span class="position mb-2">Creative arts mentor</span>
                        <div class="faded">
                            <p>Poet and theatre director, facilitating workshops on creative writing and performance.</p>
                            <ul class="ftco-social text-center">
                                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="staff">
                    <div class="img-wrap d-flex align-items-stretch">
                        <div class="img align-self-stretch" style="background-image: url(images/teacher-4.jpg);"></div>
                    </div>
                    <div class="text pt-3 text-center">
                        <h3>Esther Achieng</h3>
                        <span class="position mb-2">Projects & partnerships lead</span>
                        <div class="faded">
                            <p>Experienced in NGO coordination and youth-led grant-making initiatives.</p>
                            <ul class="ftco-social text-center">
                                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- programs (formerly courses) -->
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section ftco-animate">
                <h2 class="mb-4"><span>Flagship</span> programs</h2>
                <p>Designed by and for young people, these are the regular activities that define Al Muntadhir.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 course d-lg-flex ftco-animate">
                <div class="img" style="background-image: url(images/course-1.jpg);"></div>
                <div class="text bg-light p-4">
                    <h3><a href="#">Young facilitators lab</a></h3>
                    <p class="subheading"><span>meets:</span> Saturdays 10am–1pm</p>
                    <p>Learn how to design and lead inclusive workshops. Open to ages 18–26.</p>
                </div>
            </div>
            <div class="col-md-6 course d-lg-flex ftco-animate">
                <div class="img" style="background-image: url(images/course-2.jpg);"></div>
                <div class="text bg-light p-4">
                    <h3><a href="#">Dialogue for change</a></h3>
                    <p class="subheading"><span>meets:</span> Wednesdays 5:30pm–7pm</p>
                    <p>Structured conversations around identity, belonging, and social issues.</p>
                </div>
            </div>
            <div class="col-md-6 course d-lg-flex ftco-animate">
                <div class="img" style="background-image: url(images/course-3.jpg);"></div>
                <div class="text bg-light p-4">
                    <h3><a href="#">Art & activism</a></h3>
                    <p class="subheading"><span>meets:</span> Tuesdays 4pm–6pm</p>
                    <p>Use creative expression to campaign, tell stories, and inspire.</p>
                </div>
            </div>
            <div class="col-md-6 course d-lg-flex ftco-animate">
                <div class="img" style="background-image: url(images/course-4.jpg);"></div>
                <div class="text bg-light p-4">
                    <h3><a href="#">Youth advocacy bootcamp</a></h3>
                    <p class="subheading"><span>monthly intensive</span></p>
                    <p>From policy briefs to public speaking — skills to influence decision makers.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- stats / experience (adapted to youth forum metrics) -->
<section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url(images/bg_4.jpg);" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section heading-section-black ftco-animate">
                <h2 class="mb-4"><span>8 years of</span> youth action</h2>
                <p>Since our first gathering in 2015, we've grown into a regional network of active citizens.</p>
            </div>
        </div>
        <div class="row d-md-flex align-items-center justify-content-center">
            <div class="col-lg-10">
                <div class="row d-md-flex align-items-center">
                    <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18">
                            <div class="icon"><span class="flaticon-doctor"></span></div>
                            <div class="text">
                                <strong class="number" data-number="24">0</strong>
                                <span>Core mentors</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18">
                            <div class="icon"><span class="flaticon-doctor"></span></div>
                            <div class="text">
                                <strong class="number" data-number="450">0</strong>
                                <span>Active members</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18">
                            <div class="icon"><span class="flaticon-doctor"></span></div>
                            <div class="text">
                                <strong class="number" data-number="1200">0</strong>
                                <span>Alumni network</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18">
                            <div class="icon"><span class="flaticon-doctor"></span></div>
                            <div class="text">
                                <strong class="number" data-number="15">0</strong>
                                <span>Community partnerships</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- testimonials from members/parents -->
<section class="ftco-section testimony-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section ftco-animate">
                <h2 class="mb-4"><span>What members</span> say about the forum</h2>
                <p>Real voices from our community.</p>
            </div>
        </div>
        <div class="row ftco-animate justify-content-center">
            <div class="col-md-12">
                <div class="carousel-testimony owl-carousel">
                    <div class="item">
                        <div class="testimony-wrap d-flex">
                            <div class="user-img mr-4" style="background-image: url(images/teacher-1.jpg)"></div>
                            <div class="text ml-2 bg-light">
                                <span class="quote d-flex align-items-center justify-content-center"><i class="icon-quote-left"></i></span>
                                <p>Al Muntadhir gave me the confidence to start my own community library project. The mentorship was life‑changing.</p>
                                <p class="name">Salim Mpagi</p>
                                <span class="position">member since 2021</span>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="testimony-wrap d-flex">
                            <div class="user-img mr-4" style="background-image: url(images/teacher-2.jpg)"></div>
                            <div class="text ml-2 bg-light">
                                <span class="quote d-flex align-items-center justify-content-center"><i class="icon-quote-left"></i></span>
                                <p>I found my voice here. The dialogues are deep, respectful and push you to think critically.</p>
                                <p class="name">Amina Naluyima</p>
                                <span class="position">forum facilitator</span>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="testimony-wrap d-flex">
                            <div class="user-img mr-4" style="background-image: url(images/teacher-3.jpg)"></div>
                            <div class="text ml-2 bg-light">
                                <span class="quote d-flex align-items-center justify-content-center"><i class="icon-quote-left"></i></span>
                                <p>As a parent, I've seen my daughter become more articulate and engaged in social issues. Grateful for this space.</p>
                                <p class="name">Jalia Ssemanda</p>
                                <span class="position">parent</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- quote request / join form -->
<section class="ftco-section ftco-consult ftco-no-pt ftco-no-pb" style="background-image: url(images/bg_5.jpg);" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-md-6 py-5 px-md-5 bg-primary">
                <div class="heading-section heading-section-white ftco-animate mb-5">
                    <h2 class="mb-4">Want to get involved?</h2>
                    <p>Fill in this quick form and one of our youth coordinators will reach out within two days.</p>
                </div>
                <form action="#" class="appointment-form ftco-animate">
                    <div class="d-md-flex">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="First name">
                        </div>
                        <div class="form-group ml-md-4">
                            <input type="text" class="form-control" placeholder="Last name">
                        </div>
                    </div>
                    <div class="d-md-flex">
                        <div class="form-group">
                            <div class="form-field">
                                <div class="select-wrap">
                                    <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                    <select name="" id="" class="form-control">
                                        <option value="">I'm interested in...</option>
                                        <option value="">Mentorship</option>
                                        <option value="">Workshops</option>
                                        <option value="">Volunteering</option>
                                        <option value="">Partnerships</option>
                                        <option value="">General inquiry</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ml-md-4">
                            <input type="text" class="form-control" placeholder="Phone / WhatsApp">
                        </div>
                    </div>
                    <div class="d-md-flex">
                        <div class="form-group">
                            <textarea name="" id="" cols="30" rows="2" class="form-control" placeholder="A short message (optional)"></textarea>
                        </div>
                        <div class="form-group ml-md-4">
                            <input type="submit" value="Send request" class="btn btn-secondary py-3 px-4">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- membership tiers (formerly pricing) -->
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section ftco-animate">
                <h2 class="mb-4"><span>Membership</span> options</h2>
                <p>Al Muntadhir is mostly free, but we offer supporter tiers for those who can contribute.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="pricing-entry bg-light pb-4 text-center">
                    <div><h3 class="mb-3">Active member</h3><p><span class="price">free</span> <span class="per">/ forever</span></p></div>
                    <div class="img" style="background-image: url(images/bg_1.jpg);"></div>
                    <div class="px-4"><p>Access to all forums, workshops and mentoring. Just bring your energy.</p></div>
                    <p class="button text-center"><a href="#" class="btn btn-primary px-4 py-3">Sign up free</a></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="pricing-entry bg-light pb-4 text-center">
                    <div><h3 class="mb-3">Supporter</h3><p><span class="price">$5</span> <span class="per">/ month</span></p></div>
                    <div class="img" style="background-image: url(images/bg_2.jpg);"></div>
                    <div class="px-4"><p>Helps cover venue costs + materials. Includes supporter badge.</p></div>
                    <p class="button text-center"><a href="#" class="btn btn-secondary px-4 py-3">Become supporter</a></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="pricing-entry bg-light active pb-4 text-center">
                    <div><h3 class="mb-3">Partner</h3><p><span class="price">$25</span> <span class="per">/ month</span></p></div>
                    <div class="img" style="background-image: url(images/bg_3.jpg);"></div>
                    <div class="px-4"><p>For organisations or individuals wanting to sustain youth programs.</p></div>
                    <p class="button text-center"><a href="#" class="btn btn-tertiary px-4 py-3">Partner with us</a></p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 ftco-animate">
                <div class="pricing-entry bg-light pb-4 text-center">
                    <div><h3 class="mb-3">Alumni</h3><p><span class="price">pay it forward</span></p></div>
                    <div class="img" style="background-image: url(images/bg_5.jpg);"></div>
                    <div class="px-4"><p>Once a member, always part of the network. Stay connected & mentor.</p></div>
                    <p class="button text-center"><a href="#" class="btn btn-quarternary px-4 py-3">Stay connected</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- recent blog posts (with forum topics) -->
<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-2">
            <div class="col-md-8 text-center heading-section ftco-animate">
                <h2 class="mb-4"><span>From our</span> blog</h2>
                <p>Stories, reflections, and announcements by forum members.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4 ftco-animate">
                <div class="blog-entry">
                    <a href="blog-single.html" class="block-20 d-flex align-items-end" style="background-image: url('images/image_1.jpg');">
                        <div class="meta-date text-center p-2"><span class="day">14</span><span class="mos">March</span><span class="yr">2025</span></div>
                    </a>
                    <div class="text bg-white p-4">
                        <h3 class="heading"><a href="#">Five lessons from our intergenerational dialogue</a></h3>
                        <p>Young facilitators reflect on a powerful exchange with community elders.</p>
                        <div class="d-flex align-items-center mt-4">
                            <p class="mb-0"><a href="#" class="btn btn-secondary">Read more <span class="ion-ios-arrow-round-forward"></span></a></p>
                            <p class="ml-auto mb-0"><a href="#" class="mr-2">Amina</a><a href="#" class="meta-chat"><span class="icon-chat"></span> 8</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 ftco-animate">
                <div class="blog-entry">
                    <a href="blog-single.html" class="block-20 d-flex align-items-end" style="background-image: url('images/image_2.jpg');">
                        <div class="meta-date text-center p-2"><span class="day">22</span><span class="mos">February</span><span class="yr">2025</span></div>
                    </a>
                    <div class="text bg-white p-4">
                        <h3 class="heading"><a href="#">Artivism: murals that speak</a></h3>
                        <p>How our creative crew turned a wall into a statement on climate justice.</p>
                        <div class="d-flex align-items-center mt-4">
                            <p class="mb-0"><a href="#" class="btn btn-secondary">Read more <span class="ion-ios-arrow-round-forward"></span></a></p>
                            <p class="ml-auto mb-0"><a href="#" class="mr-2">Lubowa</a><a href="#" class="meta-chat"><span class="icon-chat"></span> 12</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 ftco-animate">
                <div class="blog-entry">
                    <a href="blog-single.html" class="block-20 d-flex align-items-end" style="background-image: url('images/image_3.jpg');">
                        <div class="meta-date text-center p-2"><span class="day">05</span><span class="mos">January</span><span class="yr">2025</span></div>
                    </a>
                    <div class="text bg-white p-4">
                        <h3 class="heading"><a href="#">Meet the new youth steering committee</a></h3>
                        <p>Seven dynamic members who will shape our 2025 agenda.</p>
                        <div class="d-flex align-items-center mt-4">
                            <p class="mb-0"><a href="#" class="btn btn-secondary">Read more <span class="ion-ios-arrow-round-forward"></span></a></p>
                            <p class="ml-auto mb-0"><a href="#" class="mr-2">Esther</a><a href="#" class="meta-chat"><span class="icon-chat"></span> 21</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- gallery / instagram style -->
<section class="ftco-gallery">
    <div class="container-wrap">
        <div class="row no-gutters">
            <div class="col-md-3 ftco-animate">
                <a href="images/image_1.jpg" class="gallery image-popup img d-flex align-items-center" style="background-image: url(images/course-1.jpg);">
                    <div class="icon mb-4 d-flex align-items-center justify-content-center"><span class="icon-instagram"></span></div>
                </a>
            </div>
            <div class="col-md-3 ftco-animate">
                <a href="images/image_2.jpg" class="gallery image-popup img d-flex align-items-center" style="background-image: url(images/image_2.jpg);">
                    <div class="icon mb-4 d-flex align-items-center justify-content-center"><span class="icon-instagram"></span></div>
                </a>
            </div>
            <div class="col-md-3 ftco-animate">
                <a href="images/image_3.jpg" class="gallery image-popup img d-flex align-items-center" style="background-image: url(images/image_3.jpg);">
                    <div class="icon mb-4 d-flex align-items-center justify-content-center"><span class="icon-instagram"></span></div>
                </a>
            </div>
            <div class="col-md-3 ftco-animate">
                <a href="images/image_4.jpg" class="gallery image-popup img d-flex align-items-center" style="background-image: url(images/image_4.jpg);">
                    <div class="icon mb-4 d-flex align-items-center justify-content-center"><span class="icon-instagram"></span></div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- footer -->
<footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">Al Muntadhir Youth Forum</h2>
                    <div class="block-23 mb-3">
                        <ul>
                            <li><span class="icon icon-map-marker"></span><span class="text">22 Youth Avenue, Kampala, Uganda</span></li>
                            <li><a href="#"><span class="icon icon-phone"></span><span class="text">+256 700 123 456</span></a></li>
                            <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@almuntadhiryouth.org</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">Recent blog posts</h2>
                    <div class="block-21 mb-4 d-flex">
                        <a class="blog-img mr-4" style="background-image: url(images/image_1.jpg);"></a>
                        <div class="text">
                            <h3 class="heading"><a href="#">Youth statement at local government meeting</a></h3>
                            <div class="meta"><div><a href="#"><span class="icon-calendar"></span> Feb 12, 2025</a></div></div>
                        </div>
                    </div>
                    <div class="block-21 mb-5 d-flex">
                        <a class="blog-img mr-4" style="background-image: url(images/image_2.jpg);"></a>
                        <div class="text">
                            <h3 class="heading"><a href="#">Call for artists: heritage month</a></h3>
                            <div class="meta"><div><a href="#"><span class="icon-calendar"></span> Jan 28, 2025</a></div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5 ml-md-4">
                    <h2 class="ftco-heading-2">Quick links</h2>
                    <ul class="list-unstyled">
                        <li><a href="#"><span class="ion-ios-arrow-round-forward mr-2"></span>Home</a></li>
                        <li><a href="#"><span class="ion-ios-arrow-round-forward mr-2"></span>About us</a></li>
                        <li><a href="#"><span class="ion-ios-arrow-round-forward mr-2"></span>Programs</a></li>
                        <li><a href="#"><span class="ion-ios-arrow-round-forward mr-2"></span>Get involved</a></li>
                        <li><a href="#"><span class="ion-ios-arrow-round-forward mr-2"></span>Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2">Subscribe</h2>
                    <form action="#" class="subscribe-form">
                        <div class="form-group">
                            <input type="text" class="form-control mb-2 text-center" placeholder="your@email.com">
                            <input type="submit" value="Subscribe" class="form-control submit px-3">
                        </div>
                    </form>
                </div>
                <div class="ftco-footer-widget mb-5">
                    <h2 class="ftco-heading-2 mb-0">Connect</h2>
                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-3">
                        <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                        <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | Al Muntadhir Youth Forum | Designed with <i class="icon-heart" aria-hidden="true"></i> by youth for youth</p>
            </div>
        </div>
    </div>
</footer>

<!-- loader -->
<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>

<script src="js/jquery.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/jquery.animateNumber.min.js"></script>
<script src="js/scrollax.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
<script src="js/google-map.js"></script>
<script src="js/main.js"></script>

</body>
</html>