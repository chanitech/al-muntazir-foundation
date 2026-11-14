<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    public function run()
    {
        // Arusha
        $arusha = Region::where('name', 'Arusha')->first();
        if ($arusha) {
            $arusha->districts()->createMany([
                ['name' => 'Arusha City'],
                ['name' => 'Arusha Rural'],
                ['name' => 'Karatu'],
                ['name' => 'Longido'],
                ['name' => 'Meru'],
                ['name' => 'Monduli'],
                ['name' => 'Ngorongoro'],
            ]);
        }

        // Dar es Salaam
        $dar = Region::where('name', 'Dar es Salaam')->first();
        if ($dar) {
            $dar->districts()->createMany([
                ['name' => 'Ilala'],
                ['name' => 'Kigamboni'],
                ['name' => 'Kinondoni'],
                ['name' => 'Temeke'],
                ['name' => 'Ubungo'],
            ]);
        }

        // Dodoma
        $dodoma = Region::where('name', 'Dodoma')->first();
        if ($dodoma) {
            $dodoma->districts()->createMany([
                ['name' => 'Bahi'],
                ['name' => 'Chamwino'],
                ['name' => 'Chemba'],
                ['name' => 'Dodoma City'],
                ['name' => 'Kondoa'],
                ['name' => 'Kongwa'],
                ['name' => 'Mpwapwa'],
            ]);
        }

        // Geita
        $geita = Region::where('name', 'Geita')->first();
        if ($geita) {
            $geita->districts()->createMany([
                ['name' => 'Bukombe'],
                ['name' => 'Chato'],
                ['name' => 'Geita'],
                ['name' => 'Mbogwe'],
                ['name' => 'Nyang\'hwale'],
            ]);
        }

        // Iringa
        $iringa = Region::where('name', 'Iringa')->first();
        if ($iringa) {
            $iringa->districts()->createMany([
                ['name' => 'Iringa Rural'],
                ['name' => 'Iringa Urban'],
                ['name' => 'Kilolo'],
                ['name' => 'Mafinga'],
            ]);
        }

        // Kagera
        $kagera = Region::where('name', 'Kagera')->first();
        if ($kagera) {
            $kagera->districts()->createMany([
                ['name' => 'Biharamulo'],
                ['name' => 'Bukoba Rural'],
                ['name' => 'Bukoba Urban'],
                ['name' => 'Karagwe'],
                ['name' => 'Kyerwa'],
                ['name' => 'Misenyi'],
                ['name' => 'Muleba'],
                ['name' => 'Ngara'],
            ]);
        }

        // Katavi
        $katavi = Region::where('name', 'Katavi')->first();
        if ($katavi) {
            $katavi->districts()->createMany([
                ['name' => 'Mlele'],
                ['name' => 'Mpimbwe'],
                ['name' => 'Nsimbo'],
                ['name' => 'Tanganyika'],
            ]);
        }

        // Kigoma
        $kigoma = Region::where('name', 'Kigoma')->first();
        if ($kigoma) {
            $kigoma->districts()->createMany([
                ['name' => 'Buhigwe'],
                ['name' => 'Kakonko'],
                ['name' => 'Kasulu Rural'],
                ['name' => 'Kasulu Urban'],
                ['name' => 'Kibondo'],
                ['name' => 'Kigoma Rural'],
                ['name' => 'Kigoma Urban'],
                ['name' => 'Uvinza'],
            ]);
        }

        // Kilimanjaro
        $kilimanjaro = Region::where('name', 'Kilimanjaro')->first();
        if ($kilimanjaro) {
            $kilimanjaro->districts()->createMany([
                ['name' => 'Hai'],
                ['name' => 'Moshi Rural'],
                ['name' => 'Moshi Urban'],
                ['name' => 'Mwanga'],
                ['name' => 'Rombo'],
                ['name' => 'Same'],
                ['name' => 'Siha'],
            ]);
        }

        // Lindi
        $lindi = Region::where('name', 'Lindi')->first();
        if ($lindi) {
            $lindi->districts()->createMany([
                ['name' => 'Kilwa'],
                ['name' => 'Lindi Rural'],
                ['name' => 'Lindi Urban'],
                ['name' => 'Liwale'],
                ['name' => 'Nachingwea'],
                ['name' => 'Ruangwa'],
            ]);
        }

        // Manyara
        $manyara = Region::where('name', 'Manyara')->first();
        if ($manyara) {
            $manyara->districts()->createMany([
                ['name' => 'Babati Rural'],
                ['name' => 'Babati Urban'],
                ['name' => 'Hanang'],
                ['name' => 'Kiteto'],
                ['name' => 'Mbulu'],
                ['name' => 'Simanjiro'],
            ]);
        }

        // Mara
        $mara = Region::where('name', 'Mara')->first();
        if ($mara) {
            $mara->districts()->createMany([
                ['name' => 'Bunda'],
                ['name' => 'Butiama'],
                ['name' => 'Musoma Rural'],
                ['name' => 'Musoma Urban'],
                ['name' => 'Rorya'],
                ['name' => 'Serengeti'],
                ['name' => 'Tarime'],
            ]);
        }

        // Mbeya
        $mbeya = Region::where('name', 'Mbeya')->first();
        if ($mbeya) {
            $mbeya->districts()->createMany([
                ['name' => 'Busokelo'],
                ['name' => 'Chunya'],
                ['name' => 'Kyela'],
                ['name' => 'Mbarali'],
                ['name' => 'Mbeya City'],
                ['name' => 'Mbeya Rural'],
                ['name' => 'Rungwe'],
            ]);
        }

        // Morogoro
        $morogoro = Region::where('name', 'Morogoro')->first();
        if ($morogoro) {
            $morogoro->districts()->createMany([
                ['name' => 'Gairo'],
                ['name' => 'Kilombero'],
                ['name' => 'Kilosa'],
                ['name' => 'Malinyi'],
                ['name' => 'Morogoro City'],
                ['name' => 'Morogoro Rural'],
                ['name' => 'Mvomero'],
                ['name' => 'Ulanga'],
            ]);
        }

        // Mtwara
        $mtwara = Region::where('name', 'Mtwara')->first();
        if ($mtwara) {
            $mtwara->districts()->createMany([
                ['name' => 'Masasi'],
                ['name' => 'Masasi Urban'],
                ['name' => 'Mtwara Rural'],
                ['name' => 'Mtwara Urban'],
                ['name' => 'Nanyumbu'],
                ['name' => 'Newala'],
                ['name' => 'Tandahimba'],
            ]);
        }

        // Mwanza
        $mwanza = Region::where('name', 'Mwanza')->first();
        if ($mwanza) {
            $mwanza->districts()->createMany([
                ['name' => 'Bukombe'],
                ['name' => 'Geita'],
                ['name' => 'Ilemela'],
                ['name' => 'Kwimba'],
                ['name' => 'Magu'],
                ['name' => 'Misungwi'],
                ['name' => 'Nyamagana'],
                ['name' => 'Sengerema'],
                ['name' => 'Ukerewe'],
            ]);
        }

        // Njombe
        $njombe = Region::where('name', 'Njombe')->first();
        if ($njombe) {
            $njombe->districts()->createMany([
                ['name' => 'Ludewa'],
                ['name' => 'Makambako'],
                ['name' => 'Makete'],
                ['name' => 'Njombe Rural'],
                ['name' => 'Njombe Urban'],
                ['name' => 'Wanging\'ombe'],
            ]);
        }

        // Pwani
        $pwani = Region::where('name', 'Pwani')->first();
        if ($pwani) {
            $pwani->districts()->createMany([
                ['name' => 'Bagamoyo'],
                ['name' => 'Kibaha'],
                ['name' => 'Kibaha Urban'],
                ['name' => 'Kisarawe'],
                ['name' => 'Mafia'],
                ['name' => 'Mkuranga'],
                ['name' => 'Rufiji'],
            ]);
        }

        // Rukwa
        $rukwa = Region::where('name', 'Rukwa')->first();
        if ($rukwa) {
            $rukwa->districts()->createMany([
                ['name' => 'Kalambo'],
                ['name' => 'Nkasi'],
                ['name' => 'Sumbawanga'],
            ]);
        }

        // Ruvuma
        $ruvuma = Region::where('name', 'Ruvuma')->first();
        if ($ruvuma) {
            $ruvuma->districts()->createMany([
                ['name' => 'Mbinga'],
                ['name' => 'Songea Rural'],
                ['name' => 'Songea Urban'],
                ['name' => 'Tunduru'],
                ['name' => 'Namtumbo'],
                ['name' => 'Nyasa'],
            ]);
        }

        // Shinyanga
        $shinyanga = Region::where('name', 'Shinyanga')->first();
        if ($shinyanga) {
            $shinyanga->districts()->createMany([
                ['name' => 'Kahama'],
                ['name' => 'Kishapu'],
                ['name' => 'Msalala'],
                ['name' => 'Shinyanga Rural'],
                ['name' => 'Shinyanga Urban'],
                ['name' => 'Ushetu'],
            ]);
        }

        // Simiyu
        $simiyu = Region::where('name', 'Simiyu')->first();
        if ($simiyu) {
            $simiyu->districts()->createMany([
                ['name' => 'Bariadi'],
                ['name' => 'Busega'],
                ['name' => 'Itilima'],
                ['name' => 'Maswa'],
                ['name' => 'Meatu'],
            ]);
        }

        // Singida
        $singida = Region::where('name', 'Singida')->first();
        if ($singida) {
            $singida->districts()->createMany([
                ['name' => 'Ikungi'],
                ['name' => 'Iramba'],
                ['name' => 'Manyoni'],
                ['name' => 'Mkalama'],
                ['name' => 'Singida Rural'],
                ['name' => 'Singida Urban'],
            ]);
        }

        // Songwe
        $songwe = Region::where('name', 'Songwe')->first();
        if ($songwe) {
            $songwe->districts()->createMany([
                ['name' => 'Ileje'],
                ['name' => 'Mbozi'],
                ['name' => 'Momba'],
                ['name' => 'Songwe'],
                ['name' => 'Tunduma'],
            ]);
        }

        // Tabora
        $tabora = Region::where('name', 'Tabora')->first();
        if ($tabora) {
            $tabora->districts()->createMany([
                ['name' => 'Igunga'],
                ['name' => 'Kaliua'],
                ['name' => 'Nzega'],
                ['name' => 'Sikonge'],
                ['name' => 'Tabora Urban'],
                ['name' => 'Urambo'],
                ['name' => 'Uyui'],
            ]);
        }

        // Tanga
        $tanga = Region::where('name', 'Tanga')->first();
        if ($tanga) {
            $tanga->districts()->createMany([
                ['name' => 'Handeni'],
                ['name' => 'Handeni Urban'],
                ['name' => 'Kilindi'],
                ['name' => 'Korogwe'],
                ['name' => 'Korogwe Urban'],
                ['name' => 'Lushoto'],
                ['name' => 'Mkinga'],
                ['name' => 'Muheza'],
                ['name' => 'Pangani'],
                ['name' => 'Tanga City'],
            ]);
        }
    }
}