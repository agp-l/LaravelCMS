@extends($layout ?? 'layouts.default.app')


@section('title', 'Projekt: Přístup ke vzdělání pro každé dítě')

@section('content')








<div class="container py-5">

    <div class="px-4 py-1 my-0 mt-5 text-center">
        <h1 class="display-6 fw-bold text-black">KidHub – prostor pro růst a nápady</h1>
        <div class="col-lg-8 mx-auto">
            <!-- Lehce přeformulovaný text -->
            <p class="lead mb-4">Každý příspěvek posouvá náš projekt k vytvoření platformy sebeřízeného vzdělávání a inovací.</p>
        </div>
    </div>

    <!-- Projekt box s carouselem vlevo a textem vpravo -->
    <div class="row g-5 align-items-center mt-0">
        <div class="col-md-6">
            <!-- Carousel: slide show místo statického obrázku -->
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="/img/uploads/24/b2.jpg" class="d-block w-100 rounded shadow-sm" alt="Slide 1">
                    </div>
                    <div class="carousel-item">
                        <img src="/img/uploads/24/b3.jpg" class="d-block w-100 rounded shadow-sm" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="/img/uploads/24/b4.jpg" class="d-block w-100 rounded shadow-sm" alt="Slide 3">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            </div>
            <div class="mt-5">
                <div class="bg-light rounded p-4 shadow-sm">
                    <h4 class="text-primary">Váš příspěvek má smysl</h4>
                    <p>
                    
                        Každá vaše investice pomáhá umožnit dětem účastnit se kurzů a výletů, objevovat svět, učit se nové dovednosti.
                        Každá investice posouvá náš projekt k vytvoření inovativní platformy pro sebeřízené vzdělávání, která nabízí příležitost a svobodu pro budoucí generace.
                    </p>

                    <p>Každý příspěvek pomáhá vytvořit prostředí plné radosti, respektu a svobody. </p>

                </div>
            </div>
        </div>
        <div class="col-md-6">

            <h3 class="text-black mb-3">Investujte do vzdělání dětí</h3>
            <p class="mb-4">Některé děti by se rády účastnily našich výletů a kurzů, jejichž rodiče si to nemohou dovolit. Vaše podpora pokryje náklady na zkušeného lektora, který se dětem bude naplno věnovat a vytvoří pro ně respektující a inspirující prostředí.
            </p>


            <!-- Upravený titulek s důrazem na investici -->
            <h3 class="text-black mb-3">Malí tvůrci ale velké nápady</h3>
            <p class="mb-4">
                Zde můžete snadno financovat inovativní projekt, Vaše investice otevírá dětem cestu k prostředí,
                který staví na principech sebeřízeného vzdělávání, svobodě, respekt a osobní růst.
            </p>

         
            <!-- Progress bar -->
            <div class="progress mb-3" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-striped bg-warning" style="width: 45%"></div>
            </div>  
            <p class="text-muted small">Vybráno 4 500 Kč z 20 000 Kč</p>
            
            <!-- Box kolem počtu hodin -->
            <div class="border border-primary rounded p-2 mb-3 d-inline-block">
                <strong>200 hodin</strong>
            </div>
            
            <p class="text-muted small">
                Vaše investice přináší zážitky, buduje vztahy a podporuje prostředí svobody, kde se učí nejen znalosti, ale i hodnoty samostatnosti.
                
            </p>

            <!-- Upravené tlačítko s výzvou k investici -->
            <a href="#" class="btn btn-primary">
                <i class="fad fa-donate me-2"></i>Přispět – investujte ihned
            </a>
            <p>Každá částka je krokem kupředu.</p>

            <!-- Další informace -->
          
        </div>
    </div>
</div>

{{-- 
Nápady na další formulace, které lze použít v rámci projektu:

1. "Investujte do budoucnosti – každý příspěvek rozvíjí platformu sebeřízeného vzdělávání a přináší inovativní prostředí, kde se rodí nové příležitosti."
2. "Vaše investice je klíčovým prvkem projektu, který staví na hodnotách sebeřízení, respektu a svobody. Kupujete produkt, který rozvíjí potenciál budoucnosti."
3. "Podpořte projekt a investujte do platformy, kde se mládež učí nejen akademické dovednosti, ale i životní zkušenosti a hodnoty samostatnosti."
4. "Přispějte a staňte se součástí inovativního projektu, který mění způsob vzdělávání – investujete do budoucnosti plné příležitostí."
--}}


<div class="container py-5">

    <div class="px-4 py-1 my-0 mt-5 text-center">
        <h1 class="display-6 fw-bold text-black">Pomozte dětem k vzdělání a zážitkům</h1>
        <div class="col-lg-8 mx-auto">
            <p class="lead mb-4">Každý příspěvek pomáhá umožnit dětem účastnit se kurzů a výletů, objevovat svět, učit se nové dovednosti. </p>
        </div>
    </div>.

    <!-- Projekt box s obrázkem vlevo a textem vpravo -->
    <div class="row g-5 align-items-center mt-0">
        <div class="col-md-6">
            <img src="/img/uploads/24/b2.jpg" class="img-fluid rounded shadow-sm" alt="Děti na výletě">
        </div>
        <div class="col-md-6">
            <h3 class="text-black mb-3">Pomáháte vytvořit svět, kde žádné dítě nemusí zůstat doma</h3>
            <p class="mb-4">Některé děti by se rády účastnily našich výletů a kurzů, jejichž rodiče si to nemohou dovolit. Vaše podpora pokryje náklady na zkušeného lektora, který se dětem bude naplno věnovat a vytvoří pro ně respektující a inspirující prostředí.
            </p>

             
            <!-- Progress bar -->
            <div class="progress mb-3" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-striped bg-warning" style="width: 45%"></div>
            </div>  
            <p class="text-muted small">Vybráno 4 500 Kč z 20 000 Kč</p>
            <p>200 hodin</p>
            <p class="text-muted small">Vaše pomoc přináší zážitky, buduje vztahy, vytváří nezapomenutelné momenty a podporuje </p>

            <a href="#" class="btn btn-primary"><i class="fad fa-donate me-2"></i>Přispět můžete ihned vložením bankovek</a>
            <p> Každá částka pomáhá. </p>


                <!-- Další informace -->
    <div class="mt-5">
        <div class="bg-light rounded p-4 shadow-sm">
            <h4 class="text-primary">Vaše pomoc má smysl</h4>
            <p>Každý příspěvek pomáhá vytvořit prostředí plné radosti, respektu a svobody. </p>
        </div>
    </div>
        </div>
    </div>


   
</div>



Různé formulace textů:
Mnoho dětí touží objevovat svět, učit se nové dovednosti a být součástí něčeho většího. Díky vašemu příspěvku to bude možné i pro ty, jejichž rodiče si to nemohou dovolit. Věříme v respektující přístup, podporu svobody a rozvoj každého dítěte. Pomáháte vytvořit svět, kde žádné dítě není pozadu jen kvůli penězům.
které by si jinak nemohly dovolit.
Podpořte dobrodružné projekty pro děti
Váš příspěvek zafinancuje zkušeného lektora, pomůcky a dopravu tak, aby žádné dítě nemuselo zůstat doma.
Tento projekt jim dává šanci. Váš příspěvek zafinancuje zkušeného lektora, pomůcky a dopravu tak, aby žádné dítě nemuselo zůstat doma.
Díky vašemu příspěvku to bude možné i pro ty, jejichž rodiče si to nemohou dovolit. 
Díky vašemu příspěvku mohou i tyto děti zažít dobrodružství, vzdělávat se v respektujícím prostředí. Vaše podpora pokryje náklady na zkušeného lektora, který se dětem bude naplno věnovat a vytvoří pro ně bezpečné a inspirativní prostředí.
Váš příspěvek zafinancuje zkušeného lektora, pomůcky a dopravu tak, aby žádné dítě nemuselo zůstat doma.

Bohužel, ne všechny rodiny si mohou dovolit zaplatit dětem tyto aktivity. 


Mnoho dětí touží objevovat svět, učit se nové dovednosti a účastnit se našich kurzů a výprav. Bohužel, ne všechny rodiny si mohou dovolit zaplatit dětem tyto aktivity.  
Vaše pomoc přináší zážitky, buduje vztahy, vytváří nezapomenutelné momenty a podporuje 



 <!-- Kontakt nebo výzva -->
 <div class="text-center mt-5">
    <h5 class="text-primary mb-3">Chcete se zapojit i jinak?</h5>
    <p>Ozvěte se nám, pokud byste chtěli přispět jako lektor, dobrovolník nebo pomoci s organizací.</p>
    <a href="/kontakt" class="btn btn-outline-primary">Kontaktujte nás</a>
</div>









<div class="container py-5">

    <div class="px-4 py-1 my-0 mt-5 text-center">
        <h1 class="display-6 fw-bold text-black">Podpořte dobrodružné projekty pro děti</h1>
        <div class="col-lg-8 mx-auto">
            <p class="lead mb-4">Každý příspěvek pomáhá vytvořit prostředí plné radosti, respektu a svobody. Vyberte si projekt, který vám dává smysl.</p>
        </div>
    </div>


  

    <!-- Karty s projekty -->
    <div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
        @foreach (range(1,3) as $i)
        <div class="col">
            <div class="card h-100 shadow-sm border-light">
                <img src="/img/uploads/24/b2.jpg" class="card-img-top" alt="Projekt {{ $i }}">
                <div class="card-body">
                    <h5 class="card-title text-primary">Projekt č.{{ $i }}</h5>
                    <p class="card-text">Pomozte nám uskutečnit tento výjimečný projekt zaměřený na rozvoj dětí a pobyt v přírodě.</p>

                    <!-- Progress bar -->
                    <div class="progress mb-2" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar progress-bar-striped bg-warning" style="width: 30%"></div>
                    </div>
                    <p class="text-muted small">Vybráno 3 000 Kč z 10 000 Kč</p>

                    <a href="#" class="btn btn-primary w-100">Přispět <i class="fad fa-donate ms-2"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Info sekce -->
    <div class="mt-5">
        <div class="bg-light rounded p-4">
            <h4>Proč přispět?</h4>
            <p>Mnoho dětí touží objevovat svět, učit se nové dovednosti a účastnit se našich kurzů a výprav. Bohužel, ne všechny rodiny si mohou dovolit zaplatit dětem tyto aktivity. Díky vašemu příspěvku mohou i tyto děti zažít dobrodružství, vzdělávat se v respektujícím prostředí a růst v komunitě. Vaše podpora pokryje náklady na zkušeného lektora, který se dětem bude naplno věnovat a vytvoří pro ně bezpečné a inspirativní prostředí.  Vaše pomoc přináší zážitky, buduje vztahy, vytváří nezapomenutelné momenty a podporuje svobodnou výchovu dětí.
                </p>
        </div>
    </div>


  

</div>



     <!-- Pruh s ikonami -->
     <div class="bg-light rounded mt-5 shadow-sm">
        <div class="container px-4 py-4">
            <div class="row row-cols-1 row-cols-lg-5 g-4 text-center">
                <div class="feature col text-center">
                    <h3></h3>
                    <p><i class="fad fa-users text-primary" style="font-size: 45px"></i></p>
                    <h5>Kamarádi                    </h5>
                </div>
                <div class="feature col text-center">
                    <p><i class="fad fa-campground text-primary" style="font-size: 45px"></i></p>
                    <h5>Dobrodružství                    </h5>
                </div>
                <div class="feature col text-center">
                    <p><i class="fad fa-tree text-primary" style="font-size: 45px"></i></p>
                    <h5>Pobyt v přírodě                    </h5>
                </div>
                <div class="feature col text-center">
                    <p><i class="fad fa-compass text-primary" style="font-size: 45px"></i></p>
                    <h5>Dovednosti                    </h5>
                </div>
                <div class="feature col text-center">
                    <p><i class="fad fa-users text-primary" style="font-size: 45px"></i></p>
                    <h5>Respekt                    </h5>
                </div>
            </div>
        </div>
    </div>


<div class=" bg-light">
    <div class="container px-4 mt-4 mt-5 my-4 py-0">
        <div class=" row g-4 py-5 row-cols-1 row-cols-lg-5">
            <div class="feature col text-center">
                <p><i class="fad fa-users text-primary" style="font-size: 45px"></i></p>
                <h5>Kamarádi                    </h5>
            </div>
            <div class="feature col text-center">
                <p><i class="fad fa-campground text-primary" style="font-size: 45px"></i></p>
                <h5>Dobrodružství                    </h5>
            </div>
            <div class="feature col text-center">
                <p><i class="fad fa-tree text-primary" style="font-size: 45px"></i></p>
                <h5>Pobyt v přírodě                    </h5>
            </div>
            <div class="feature col text-center">
                <p><i class="fad fa-compass text-primary" style="font-size: 45px"></i></p>
                <h5>Dovednosti                    </h5>
            </div>
            <div class="feature col text-center">
                <p><i class="fad fa-users text-primary" style="font-size: 45px"></i></p>
                <h5>Respekt                    </h5>
            </div>
        </div>
    </div>
</div>  

@endsection
