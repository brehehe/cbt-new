<?php

namespace Database\Seeders\Consultation;

use App\Models\Master\CodeSystem\Consultation\MasterConsultationSnomedCT;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterConsultationSnomedCTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterConsultationSnomedCT::query()->delete();

        $datas = [
            [
                "Code" => 116289008,
                "Display" => "Abdominal bloating",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kembung"
            ],
            [
                "Code" => 398212009,
                "Display" => "Watery stool",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "BAB cair"
            ],
            [
                "Code" => 398032003,
                "Display" => "Loose stool",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "BAB lembek"
            ],
            [
                "Code" => 422587007,
                "Display" => "Nausea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mual"
            ],
            [
                "Code" => 16932000,
                "Display" => "Nausea and vomiting",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mual dan muntah"
            ],
            [
                "Code" => 386661006,
                "Display" => "Fever",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Demam"
            ],
            [
                "Code" => 21522001,
                "Display" => "Abdominal pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri perut"
            ],
            [
                "Code" => 34095006,
                "Display" => "Dehydration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Dehidrasi"
            ],
            [
                "Code" => 405729008,
                "Display" => "Bloody stool",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "BAB darah"
            ],
            [
                "Code" => 271864008,
                "Display" => "Mucus stool",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "BAB lendir"
            ],
            [
                "Code" => 422400008,
                "Display" => "Vomiting",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Muntah"
            ],
            [
                "Code" => 49727002,
                "Display" => "Cough",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Batuk"
            ],
            [
                "Code" => 84229001,
                "Display" => "Fatigue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kelelahan"
            ],
            [
                "Code" => 367391008,
                "Display" => "Malaise",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Malaise"
            ],
            [
                "Code" => 43724002,
                "Display" => "Chill",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Menggigil"
            ],
            [
                "Code" => 57676002,
                "Display" => "Arthralgia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri sendi"
            ],
            [
                "Code" => 62315008,
                "Display" => "Diarrhea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Diare"
            ],
            [
                "Code" => 18165001,
                "Display" => "Jaundice",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kuning"
            ],
            [
                "Code" => 25064002,
                "Display" => "Headache",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri kepala"
            ],
            [
                "Code" => 91175000,
                "Display" => "Seizure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kejang"
            ],
            [
                "Code" => 40917007,
                "Display" => "Clouded consciousness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kebingungan"
            ],
            [
                "Code" => 415690000,
                "Display" => "Sweating",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Berkeringat"
            ],
            [
                "Code" => 68962001,
                "Display" => "Muscle pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri otot"
            ],
            [
                "Code" => 79890006,
                "Display" => "Loss of appetite",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hilang nafsu makan"
            ],
            [
                "Code" => 64379006,
                "Display" => "Decrease in appetite",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Penurunan nafsu makan"
            ],
            [
                "Code" => 271807003,
                "Display" => "Skin rash",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Ruam kulit"
            ],
            [
                "Code" => 249366005,
                "Display" => "Bleeding from nose",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mimisan"
            ],
            [
                "Code" => 86276007,
                "Display" => "Bleeding gums",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gusi berdarah"
            ],
            [
                "Code" => 424131007,
                "Display" => "Easy bruising",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mudah memar"
            ],
            [
                "Code" => 267102003,
                "Display" => "Sore throat",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri tenggorokan"
            ],
            [
                "Code" => 50091001,
                "Display" => "Petechiae",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Petekie"
            ],
            [
                "Code" => 34436003,
                "Display" => "Hematuria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "BAK berdarah"
            ],
            [
                "Code" => 8765009,
                "Display" => "Hematemesis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hematemesis"
            ],
            [
                "Code" => 30233002,
                "Display" => "Swallowing painful",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri telan"
            ],
            [
                "Code" => 64531003,
                "Display" => "Nasal discharge",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pilek"
            ],
            [
                "Code" => 443371007,
                "Display" => "Decreased level of consciousness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Penurunan kesadaran"
            ],
            [
                "Code" => 274640006,
                "Display" => "Fever with chills",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Demam menggigil "
            ],
            [
                "Code" => 267036007,
                "Display" => "Dyspnea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Sesak nafas "
            ],
            [
                "Code" => 271823003,
                "Display" => "Tachypnea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Takipnea "
            ],
            [
                "Code" => 11833005,
                "Display" => "Dry cough",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Batuk kering"
            ],
            [
                "Code" => 28743005,
                "Display" => "Productive cough",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Batuk berdahak"
            ],
            [
                "Code" => 29857009,
                "Display" => "Chest pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri dada"
            ],
            [
                "Code" => 89362005,
                "Display" => "Weight loss",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Penurunan berat badan"
            ],
            [
                "Code" => 271795006,
                "Display" => "Malaise and fatigue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Malaise dan kelelahan"
            ],
            [
                "Code" => 267096005,
                "Display" => "Frontal headache",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri kepala bagian depan "
            ],
            [
                "Code" => 698193000,
                "Display" => "Coating of mucous membrane of tongue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Lidah kotor "
            ],
            [
                "Code" => 14760008,
                "Display" => "Constipation ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Konstipasi "
            ],
            [
                "Code" => 52613005,
                "Display" => "Excessive sweating",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Keringat berlebihan "
            ],
            [
                "Code" => 301345002,
                "Display" => "Difficulty sleeping",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Sulit tidur"
            ],
            [
                "Code" => 418290006,
                "Display" => "Itching",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gatal"
            ],
            [
                "Code" => 39575007,
                "Display" => "Urine looks dark",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Urin gelap"
            ],
            [
                "Code" => 703630003,
                "Display" => "Red eye",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mata merah"
            ],
            [
                "Code" => 289195008,
                "Display" => "Slurred speech",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Cadel"
            ],
            [
                "Code" => 76067001,
                "Display" => "Sneezing",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Bersin"
            ],
            [
                "Code" => 68235000,
                "Display" => "Nasal congestion",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hidung tersumbat"
            ],
            [
                "Code" => 193982009,
                "Display" => "Epiphora",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Epifora"
            ],
            [
                "Code" => 304213008,
                "Display" => "Low-grade fever",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Demam ringan"
            ],
            [
                "Code" => 44695005,
                "Display" => "Paralysis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Paralisis"
            ],
            [
                "Code" => 786837007,
                "Display" => "Tingling",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kesemutan"
            ],
            [
                "Code" => 288509005,
                "Display" => "Burning of skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Rasa terbakar"
            ],
            [
                "Code" => 193462001,
                "Display" => "Insomnia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Insomnia"
            ],
            [
                "Code" => 48694002,
                "Display" => "Anxiety",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Cemas"
            ],
            [
                "Code" => 44548000,
                "Display" => "Hyperactivity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hiperaktif"
            ],
            [
                "Code" => 24199005,
                "Display" => "Feeling agitated",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gelisah"
            ],
            [
                "Code" => 7011001,
                "Display" => "Hallucinations",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Halusinasi"
            ],
            [
                "Code" => 399122003,
                "Display" => "Swallowing Problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Sulit menelan"
            ],
            [
                "Code" => 399907009,
                "Display" => "Animal bite wound",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Luka gigitan hewan"
            ],
            [
                "Code" => 3424008,
                "Display" => "Tachycardia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Takikardia"
            ],
            [
                "Code" => 271687003,
                "Display" => "Swelling of scrotum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Skrotum bengkak"
            ],
            [
                "Code" => 267055007,
                "Display" => "Black feces",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "BAB hitam"
            ],
            [
                "Code" => 25064002,
                "Display" => "Headache ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Sakit kepala "
            ],
            [
                "Code" => 444237009,
                "Display" => "Risk of exposure to Leptospira (situation)",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Risiko paparan Leptospirosis "
            ],
            [
                "Code" => 444397009,
                "Display" => "Exposure to Leptospira (event)",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Paparan terhadap leptospirosis"
            ],
            [
                "Code" => 55300003,
                "Display" => "Cramp",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kram"
            ],
            [
                "Code" => 45007003,
                "Display" => "Low blood pressure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Tekanan darah rendah "
            ],
            [
                "Code" => 18425006,
                "Display" => "Passage of rice water stools (finding) |",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Feses seperti cucian beras"
            ],
            [
                "Code" => 224960004,
                "Display" => "Tired",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Lelah"
            ],
            [
                "Code" => 161882006,
                "Display" => "Stiff neck",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kaku leher"
            ],
            [
                "Code" => 214264003,
                "Display" => "Lethargy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Letargi"
            ],
            [
                "Code" => 95629002,
                "Display" => "Excessive crying of newborn",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Tangis berlebihan pada bayi baru lahir"
            ],
            [
                "Code" => 288980000,
                "Display" => "Difficulty sucking",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kesulitan saat menyusu"
            ],
            [
                "Code" => 40739000,
                "Display" => "Dysphagia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri telan"
            ],
            [
                "Code" => 419045004,
                "Display" => "Loss of consciousness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Penurunan kesadaran "
            ],
            [
                "Code" => 44169009,
                "Display" => "Anosmia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Anosmia (kehilangan penciuman) "
            ],
            [
                "Code" => 36955009,
                "Display" => "Augesia ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Augesia (kehilangan pengecapan) "
            ],
            [
                "Code" => 840546002,
                "Display" => "Exposure to severe acute respiratory syndrome coronavirus 2 (event)",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Paparan COVID-19 "
            ],
            [
                "Code" => 267023007,
                "Display" => "Excessive eating - polyphagia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Polifagia"
            ],
            [
                "Code" => 28442001,
                "Display" => "Polyuria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Poliuria"
            ],
            [
                "Code" => 17173007,
                "Display" => "Excessive thirst",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Polidipsi"
            ],
            [
                "Code" => 13791008,
                "Display" => "Asthenia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Lemah"
            ],
            [
                "Code" => 62507009,
                "Display" => "Pins and needles",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kesemutan"
            ],
            [
                "Code" => 111516008,
                "Display" => "Blurring of visual image",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mata kabur"
            ],
            [
                "Code" => 67882000,
                "Display" => "Pruritus of vulva",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pruritus vulvae"
            ],
            [
                "Code" => 789507005,
                "Display" => "Delayed healing of wound",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Luka sulit sembuh"
            ],
            [
                "Code" => 66857006,
                "Display" => "Hemoptysis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Batuk darah"
            ],
            [
                "Code" => 42984000,
                "Display" => "Night sweats",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Keringat malam"
            ],
            [
                "Code" => 248268002,
                "Display" => "Tires quickly",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mudah lelah"
            ],
            [
                "Code" => 20022000,
                "Display" => "Hemiparesis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hemiparesis"
            ],
            [
                "Code" => 50582007,
                "Display" => "Hemiplegia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hemiplegi"
            ],
            [
                "Code" => 14686007,
                "Display" => "Hemianesthesia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hemianesthesi"
            ],
            [
                "Code" => 87486003,
                "Display" => "Aphasia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Afasia"
            ],
            [
                "Code" => 20262006,
                "Display" => "Ataxia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Ataksia"
            ],
            [
                "Code" => 399153001,
                "Display" => "Vertigo",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Vertigo"
            ],
            [
                "Code" => 24982008,
                "Display" => "Diplopia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Diplopia (Penglihatan ganda)"
            ],
            [
                "Code" => 77674003,
                "Display" => "Hemianopia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Hemianopsia"
            ],
            [
                "Code" => 267095009,
                "Display" => "Speech problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gangguan bicara"
            ],
            [
                "Code" => 78691002,
                "Display" => "Staggering gait",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Jalan sempoyongan"
            ],
            [
                "Code" => 12184005,
                "Display" => "Visual field defect",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gangguan lapang pandang"
            ],
            [
                "Code" => 83547004,
                "Display" => "Cold sweat",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Keringat dingin"
            ],
            [
                "Code" => 1209208002,
                "Display" => "Pale Face",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Wajah pucat"
            ],
            [
                "Code" => 79922009,
                "Display" => "Epigastric pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri epigastrium"
            ],
            [
                "Code" => 473434003,
                "Display" => "Pain in chin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri dagu"
            ],
            [
                "Code" => 81680005,
                "Display" => "Neck pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri leher"
            ],
            [
                "Code" => 53057004,
                "Display" => "Hand pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri tangan"
            ],
            [
                "Code" => 161891005,
                "Display" => "Backache",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri punggung"
            ],
            [
                "Code" => 80313002,
                "Display" => "Palpitations",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Palpitasi"
            ],
            [
                "Code" => 404640003,
                "Display" => "Dizziness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pusing"
            ],
            [
                "Code" => 271594007,
                "Display" => "Syncope",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pingsan"
            ],
            [
                "Code" => 386813002,
                "Display" => "Abnormal breathing",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gangguan napas"
            ],
            [
                "Code" => 49650001,
                "Display" => "Dysuria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri saat BAK"
            ],
            [
                "Code" => 139394000,
                "Display" => "Nocturia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nokturia"
            ],
            [
                "Code" => 162053006,
                "Display" => "Suprapubic pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri suprapubik"
            ],
            [
                "Code" => 279039007,
                "Display" => "Low back pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri pinggang"
            ],
            [
                "Code" => 102830001,
                "Display" => "Renal angle tenderness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri pada sudut kostovertebra"
            ],
            [
                "Code" => 249279003,
                "Display" => "Must strain to pass urine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mengejan saat BAK"
            ],
            [
                "Code" => 162128006,
                "Display" => "Poor stream of urine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pancaran urin mengecil"
            ],
            [
                "Code" => 14302001,
                "Display" => "Amenorrhea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Haid terhenti"
            ],
            [
                "Code" => 51885006,
                "Display" => "Morning sickness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mual muntah pagi hari"
            ],
            [
                "Code" => 248132003,
                "Display" => "Craving for food or drink",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Ngidam"
            ],
            [
                "Code" => 372283008,
                "Display" => "Large breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pembesaran Payudara"
            ],
            [
                "Code" => 60862001,
                "Display" => "Tinnitus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Telinga mendenging"
            ],
            [
                "Code" => 7973008,
                "Display" => "Abnormal vision",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gangguan pengelihatan"
            ],
            [
                "Code" => 24184005,
                "Display" => "Elevated blood pressure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kenaikan tekanan darah"
            ],
            [
                "Code" => 50960005,
                "Display" => "Hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Perdarahan"
            ],
            [
                "Code" => 309737007,
                "Display" => "Abdominal pain in pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri perut pada kehamilan"
            ],
            [
                "Code" => 271939006,
                "Display" => "Vaginal discharge",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Keluar cairan dari vagina"
            ],
            [
                "Code" => 47821001,
                "Display" => "Postpartum hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Perdarahan setelah melahirkan"
            ],
            [
                "Code" => 246975001,
                "Display" => "Scleral icterus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mata kuning"
            ],
            [
                "Code" => 225549006,
                "Display" => "Yellow skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kulit kuning"
            ],
            [
                "Code" => 88746009,
                "Display" => "Black urine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Warna urine seperti teh"
            ],
            [
                "Code" => 70396004,
                "Display" => "Acholic stool",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Tinja seperti dempul"
            ],
            [
                "Code" => 409668002,
                "Display" => "Photophobia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Fotophobia"
            ],
            [
                "Code" => 442672001,
                "Display" => "Swelling",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pembengkakan"
            ],
            [
                "Code" => 247508004,
                "Display" => "White nails",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Kuku putih"
            ],
            [
                "Code" => 22253000,
                "Display" => "Pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Nyeri"
            ],
            [
                "Code" => 38671000119103,
                "Display" => "Abnormal urination",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Gangguan BAK"
            ],
            [
                "Code" => 300848003,
                "Display" => "Mass of body structure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Benjolan"
            ],
            [
                "Code" => 249473004,
                "Display" => "Altered appetite",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Perubahan nafsu makan"
            ],
            [
                "Code" => 275258002,
                "Display" => "Breast changes",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Perubahan bentuk payudara"
            ],
            [
                "Code" => 278528006,
                "Display" => "Facial swelling",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Pembengkakan wajah"
            ],
            [
                "Code" => 26079004,
                "Display" => "Tremor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Tremor"
            ],
            [
                "Code" => 62476001,
                "Display" => "Disorientated",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Disorientasi"
            ],
            [
                "Code" => 257552002,
                "Display" => "Inflammation",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Peradangan"
            ],
            [
                "Code" => 420103007,
                "Display" => "Watery eye",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mata berair"
            ],
            [
                "Code" => 45352006,
                "Display" => "Spasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Spasme"
            ],
            [
                "Code" => 271782001,
                "Display" => "Drowsy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "Mudah mengantuk"
            ],
            [
                "Code" => 718403007,
                "Display" => "Decreased urine output",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "BAK sedikit"
            ],
        ];

        foreach ($datas as $key => $data) {
            MasterConsultationSnomedCT::updateOrCreate([
                'code' => $data['Code'],
            ], [
                'display' => $data['Display'],
                'code_system' => $data['Code System'],
                'keterangan' => $data['Keterangan'],
                'type' => 'keluhan-utama',
            ]);
        }

        $datas2 = [
            [
                "Code" => "275556002",
                "Display" => "A/B cover need - dentistry",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267006003",
                "Display" => "A/B cover need - surg./dentist",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275557006",
                "Display" => "A/B cover need - surgery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "394698008",
                "Display" => "Birth history",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473388009",
                "Display" => "Born at home",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "169818001",
                "Display" => "Born before arrival",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "444966004",
                "Display" => "Born before arrival of midwife",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "445087001",
                "Display" => "Born before arrival to hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "407613009",
                "Display" => "Born by breech delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "394699000",
                "Display" => "Born by cesarean section",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "395682006",
                "Display" => "Born by elective cesarean section",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "407615002",
                "Display" => "Born by emergency cesarean section",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "395681004",
                "Display" => "Born by forceps delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "395683001",
                "Display" => "Born by normal vaginal delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "407614003",
                "Display" => "Born by ventouse delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473389001",
                "Display" => "Born in ambulance",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "103011000119106",
                "Display" => "Coronary arteriosclerosis in patient with history of previous myocardial infarction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187607009",
                "Display" => "Deserted from military service",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473137000",
                "Display" => "Disability absent",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187608004",
                "Display" => "Dishonorably discharged from military service",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187605001",
                "Display" => "Exposed to combat during military service",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187612005",
                "Display" => "Failed military service training",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "162271007",
                "Display" => "Felt faint",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "709462001",
                "Display" => "Fetal exposure to alcohol",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "710570008",
                "Display" => "Fetal exposure to angiotensin converting enzyme inhibitor agent",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789377005",
                "Display" => "Fetal exposure to anticonvulsant",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "711456004",
                "Display" => "Fetal exposure to drug",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "781699006",
                "Display" => "Fetal exposure to methamphetamine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "709426002",
                "Display" => "Fetal exposure to toxin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "440706004",
                "Display" => "Full renal function recovered",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161542005",
                "Display" => "H/O lower GIT neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161432005",
                "Display" => "H/O Malignant melanoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161541003",
                "Display" => "H/O upper GIT neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161513002",
                "Display" => "H/O ventricular fibrillation",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161747002",
                "Display" => "H/O=>1 miscarriage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161748007",
                "Display" => "H/O=>2 miscarriages",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161749004",
                "Display" => "H/O=>3 miscarriages",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161750004",
                "Display" => "H/O=>4 miscarriages",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161751000",
                "Display" => "H/O=>5 miscarriages",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161752007",
                "Display" => "H/O=>6 miscarriages",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161533003",
                "Display" => "H/O=>abdominal hernia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267016006",
                "Display" => "H/O=>abnormal uterine bleeding",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "309635005",
                "Display" => "H/O=>Admission in last year for diabetes foot problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161466001",
                "Display" => "H/O=>alcoholism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "390933006",
                "Display" => "H/O=>amblyopia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161780009",
                "Display" => "H/O=>amenorrhea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161456009",
                "Display" => "H/O=>anemia - iron deficient",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "308066006",
                "Display" => "H/O=>Angina in last year",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161504004",
                "Display" => "H/O=>angina pectoris",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "609550002",
                "Display" => "H/O=>angiotensin converting enzyme inhibitor pseudoallergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161471008",
                "Display" => "H/O=>anorexia nervosa",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161470009",
                "Display" => "H/O=>anxiety state",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161514008",
                "Display" => "H/O=>aortic aneurysm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161532008",
                "Display" => "H/O=>appendicitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275554004",
                "Display" => "H/O=>arthritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161635002",
                "Display" => "H/O=>asbestos exposure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161527007",
                "Display" => "H/O=>asthma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "312442005",
                "Display" => "H/O=>atrial fibrillation",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161474000",
                "Display" => "H/O=>attempted suicide",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161570007",
                "Display" => "H/O=>back problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267000009",
                "Display" => "H/O=>biliary disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "12481000132103",
                "Display" => "H/O=>biological substance allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161580006",
                "Display" => "H/O=>birth trauma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266992002",
                "Display" => "H/O=>blood disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275540007",
                "Display" => "H/O=>brain disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161797003",
                "Display" => "H/O=>breast problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275528007",
                "Display" => "H/O=>bronchitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275903009",
                "Display" => "H/O=>carcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161573009",
                "Display" => "H/O=>cardiac anomaly",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161692001",
                "Display" => "H/O=>cardiac pacemaker in situ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266995000",
                "Display" => "H/O=>cardiovascular disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161489009",
                "Display" => "H/O=>cataract",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161423008",
                "Display" => "H/O=>chickenpox",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161496006",
                "Display" => "H/O=>chronic ear infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161563007",
                "Display" => "H/O=>chronic skin ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161575002",
                "Display" => "H/O=>cleft lip",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161574003",
                "Display" => "H/O=>cleft palate",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266993007",
                "Display" => "H/O=>CNS disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161460007",
                "Display" => "H/O=>coagulation defect",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266999006",
                "Display" => "H/O=>colitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161572004",
                "Display" => "H/O=>congenital anomaly",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161491001",
                "Display" => "H/O=>corneal ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "314550003",
                "Display" => "H/O=>deliberate self harm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161465002",
                "Display" => "H/O=>dementia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161469008",
                "Display" => "H/O=>depression",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161445009",
                "Display" => "H/O=>diabetes mellitus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "413178000",
                "Display" => "H/O=>diphtheria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "135879003",
                "Display" => "H/O=>dislocated shoulder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "312850006",
                "Display" => "H/O=>Disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275547005",
                "Display" => "H/O=>duodenal ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161796007",
                "Display" => "H/O=>dyspareunia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161494009",
                "Display" => "H/O=>ear disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161806007",
                "Display" => "H/O=>eclampsia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161763005",
                "Display" => "H/O=>ectopic pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161561009",
                "Display" => "H/O=>eczema",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275545002",
                "Display" => "H/O=>embolism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161479005",
                "Display" => "H/O=>encephalitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266990005",
                "Display" => "H/O=>endocrine disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161480008",
                "Display" => "H/O=>epilepsy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "390839009",
                "Display" => "H/O=>facial injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "394705009",
                "Display" => "H/O=>febrile convulsions",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "726625000",
                "Display" => "H/O=>first degree perineal laceration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "391095006",
                "Display" => "H/O=>fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "391092009",
                "Display" => "H/O=>fragility fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267015005",
                "Display" => "H/O=>full term delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275550008",
                "Display" => "H/O=>gallbladder disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "407637009",
                "Display" => "H/O=>gallstones",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275548000",
                "Display" => "H/O=>gastric ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266997008",
                "Display" => "H/O=>gastrointestinal disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267018007",
                "Display" => "H/O=>genital prolapse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161488001",
                "Display" => "H/O=>glaucoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161451004",
                "Display" => "H/O=>gout",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161524000",
                "Display" => "H/O=>hay fever",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161587009",
                "Display" => "H/O=>head injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161497002",
                "Display" => "H/O=>hearing problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275544003",
                "Display" => "H/O=>heart disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161505003",
                "Display" => "H/O=>heart failure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "309634009",
                "Display" => "H/O=>Heart failure in last year",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "391093004",
                "Display" => "H/O=>hip fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161501007",
                "Display" => "H/O=>hypertension",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161442007",
                "Display" => "H/O=>hyperthyroidism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161443002",
                "Display" => "H/O=>hypothyroidism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161413004",
                "Display" => "H/O=>infectious disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161798008",
                "Display" => "H/O=>infertility - female",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161556007",
                "Display" => "H/O=>infertility - male",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161586000",
                "Display" => "H/O=>injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161787007",
                "Display" => "H/O=>inter-menstrual bleeding",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "310479003",
                "Display" => "H/O=>iritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161636001",
                "Display" => "H/O=>isocyanate exposure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161536006",
                "Display" => "H/O=>jaundice",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275552000",
                "Display" => "H/O=>kidney disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161547004",
                "Display" => "H/O=>kidney infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275555003",
                "Display" => "H/O=>knee problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161535005",
                "Display" => "H/O=>liver disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161700006",
                "Display" => "H/O=>machine dependence",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161554005",
                "Display" => "H/O=>male genital disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266987004",
                "Display" => "H/O=>malignant neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "400998002",
                "Display" => "H/O=>manic depressive disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161419000",
                "Display" => "H/O=>measles",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161478002",
                "Display" => "H/O=>meningitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161782001",
                "Display" => "H/O=>menorrhagia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161779006",
                "Display" => "H/O=>menstrual disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266991009",
                "Display" => "H/O=>metabolic disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161481007",
                "Display" => "H/O=>migraine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161744009",
                "Display" => "H/O=>miscarriage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161614004",
                "Display" => "H/O=>multiple allergies",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161420006",
                "Display" => "H/O=>mumps",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267004000",
                "Display" => "H/O=>musculoskeletal disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161503005",
                "Display" => "H/O=>myocardial infarct at greater than 60",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161502000",
                "Display" => "H/O=>myocardial infarct at less than 60",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "308065005",
                "Display" => "H/O=>Myocardial infarction in last year",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275905002",
                "Display" => "H/O=>myocardial problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275542004",
                "Display" => "H/O=>Ménière's disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "413141003",
                "Display" => "H/O=>needle stick injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275904003",
                "Display" => "H/O=>neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161546008",
                "Display" => "H/O=>nephritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161611007",
                "Display" => "H/O=>non-drug allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161449003",
                "Display" => "H/O=>nutritional disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161453001",
                "Display" => "H/O=>obesity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161772002",
                "Display" => "H/O=>oral contraceptive usage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161568003",
                "Display" => "H/O=>osteoarthritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275531008",
                "Display" => "H/O=>pacemaker in situ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267017002",
                "Display" => "H/O=>pelvic infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266998003",
                "Display" => "H/O=>peptic ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161583008",
                "Display" => "H/O=>perinatal convulsion",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161579008",
                "Display" => "H/O=>perinatal problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161422003",
                "Display" => "H/O=>pertussis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "413154005",
                "Display" => "H/O=>phlebitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161525004",
                "Display" => "H/O=>pneumonia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161528002",
                "Display" => "H/O=>pneumothorax",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161589007",
                "Display" => "H/O=>poisoning",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161415006",
                "Display" => "H/O=>poliomyelitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161781008",
                "Display" => "H/O=>polymenorrhea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161786003",
                "Display" => "H/O=>postcoital bleeding",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161788002",
                "Display" => "H/O=>postmenopausal bleeding",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "726623007",
                "Display" => "H/O=>postpartum psychosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "271903000",
                "Display" => "H/O=>pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161765003",
                "Display" => "H/O=>premature delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "733899004",
                "Display" => "H/O=>previous baby with fetal growth restriction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275907005",
                "Display" => "H/O=>procidentia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161810005",
                "Display" => "H/O=>prolonged labor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161555006",
                "Display" => "H/O=>prostatism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161562002",
                "Display" => "H/O=>psoriasis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161464003",
                "Display" => "H/O=>psychiatric disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161472001",
                "Display" => "H/O=>psychological trauma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161512007",
                "Display" => "H/O=>pulmonary embolus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161461006",
                "Display" => "H/O=>purpura",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161633009",
                "Display" => "H/O=>radiation exposure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161450003",
                "Display" => "H/O=>raised blood lipids",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "407586004",
                "Display" => "H/O=>recreational drug use",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161549001",
                "Display" => "H/O=>recurrent cystitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161656000",
                "Display" => "H/O=>regular medication",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "390867000",
                "Display" => "H/O=>repeated overdose",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161701005",
                "Display" => "H/O=>respirator dependence",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161523006",
                "Display" => "H/O=>respiratory disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161487006",
                "Display" => "H/O=>retinal detachment",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161500008",
                "Display" => "H/O=>rheumatic fever",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161567008",
                "Display" => "H/O=>rheumatoid arthritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161632004",
                "Display" => "H/O=>risk factor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161421005",
                "Display" => "H/O=>rubella",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161417003",
                "Display" => "H/O=>scarlatina",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161468000",
                "Display" => "H/O=>schizophrenia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161807003",
                "Display" => "H/O=>severe pre-eclampsia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267003006",
                "Display" => "H/O=>sexual function problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161775000",
                "Display" => "H/O=>sheath usage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267005004",
                "Display" => "H/O=>significant knee disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161560005",
                "Display" => "H/O=>skin disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "726597008",
                "Display" => "H/O=>spontaneous onset of labor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161743003",
                "Display" => "H/O=>stillbirth",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161794005",
                "Display" => "H/O=>stress incontinence",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "308067002",
                "Display" => "H/O=>Stroke in last year",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "266996004",
                "Display" => "H/O=>thromboembolism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275546001",
                "Display" => "H/O=>thrombosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275536003",
                "Display" => "H/O=>thyroid disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "308068007",
                "Display" => "H/O=>Treatment for ischemic heart disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161482000",
                "Display" => "H/O=>trigeminal neuralgia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161414005",
                "Display" => "H/O=>tuberculosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275549008",
                "Display" => "H/O=>ulcerative colitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161551002",
                "Display" => "H/O=>urethral stricture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161576001",
                "Display" => "H/O=>urinary anomaly",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "267002001",
                "Display" => "H/O=>urinary disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161548009",
                "Display" => "H/O=>urinary stone",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "726596004",
                "Display" => "H/O=>uterine inversion",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161509009",
                "Display" => "H/O=>varicose veins",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "391094005",
                "Display" => "H/O=>vertebral fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275543009",
                "Display" => "H/O=>vertigo",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161424002",
                "Display" => "H/O=>viral illness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161490000",
                "Display" => "H/O=>visual disturbance",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275553005",
                "Display" => "H/O:male sex function problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275571003",
                "Display" => "H/O:sexual problem - female",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "270891002",
                "Display" => "Had a collapse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "228393009",
                "Display" => "Has never injected drugs",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "228368007",
                "Display" => "Has never misused drugs",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "439720003",
                "Display" => "Has never shared drug injection equipment",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161638000",
                "Display" => "Hepatitis B Occupational risk",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "439956007",
                "Display" => "History of abnormal cervical Papanicolaou smear",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "313214000",
                "Display" => "History of abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.69155E+16",
                "Display" => "History of acalculous cholecystitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "138381000119101",
                "Display" => "History of acoustic neuroma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690711000119103",
                "Display" => "History of acquired spondylolisthesis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "48811000119106",
                "Display" => "History of acromegaly",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1361000119104",
                "Display" => "History of actinic keratosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473114006",
                "Display" => "History of active tuberculosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "157533041000119108",
                "Display" => "History of acute angle closure glaucoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16360861000119103",
                "Display" => "History of acute febrile mucocutaneous lymph node syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "400347211000119102",
                "Display" => "History of acute leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472959005",
                "Display" => "History of acute lower respiratory tract infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "676112841000119101",
                "Display" => "History of acute lymphoid leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "79081000119100",
                "Display" => "History of acute myeloid leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137971000119104",
                "Display" => "History of acute renal failure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16775141000119108",
                "Display" => "History of acute respiratory failure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "285721000119104",
                "Display" => "History of acute ST segment elevation myocardial infarction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429047008",
                "Display" => "History of adenomatous polyp of colon",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "691201000119100",
                "Display" => "History of adrenal adenoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16093531000119101",
                "Display" => "History of adult obesity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "94671000119102",
                "Display" => "History of adult respiratory distress syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "414371008",
                "Display" => "History of agoraphobia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "371434005",
                "Display" => "History of alcohol abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1141000161108",
                "Display" => "History of alcohol withdrawal syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16912131000119107",
                "Display" => "History of alcoholic hepatitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "13016361000119101",
                "Display" => "History of amaurosis fugax",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16688121000119106",
                "Display" => "History of anal intraepithelial neoplasia III",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10839421000119104",
                "Display" => "History of anaphylaxis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275538002",
                "Display" => "History of anemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161457000",
                "Display" => "History of anemia vitamin B12 deficient",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "397942001",
                "Display" => "History of anesthesia problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432250006",
                "Display" => "History of aneurysm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789474004",
                "Display" => "History of aneurysm of artery of trunk",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "433845004",
                "Display" => "History of aneurysm of iliac artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789475003",
                "Display" => "History of aneurysm of peripheral artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16542721000119108",
                "Display" => "History of anoxic brain injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161804005",
                "Display" => "History of antepartum hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.60937E+16",
                "Display" => "History of anterior ischemic optic neuropathy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "440700005",
                "Display" => "History of aortoiliac atherosclerosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429208004",
                "Display" => "History of aplastic anemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16833281000119106",
                "Display" => "History of arterial thrombosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472975008",
                "Display" => "History of arteritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10824251000119108",
                "Display" => "History of artery embolism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "154081000119108",
                "Display" => "History of aspiration pneumonia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429258006",
                "Display" => "History of aspirin exacerbated respiratory disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73831000119109",
                "Display" => "History of astrocytoma of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432218001",
                "Display" => "History of asymptomatic human immunodeficiency virus infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428448003",
                "Display" => "History of atopy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428076002",
                "Display" => "History of atrial flutter",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "328001000119104",
                "Display" => "History of atrial myxoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473390005",
                "Display" => "History of attempted weight loss",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "28271000087107",
                "Display" => "History of attention deficit hyperactivity disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "143491000119100",
                "Display" => "History of atypical hyperplasia of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1351000119101",
                "Display" => "History of atypical nevus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472968007",
                "Display" => "History of autoimmune disorder of endocrine system",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429217004",
                "Display" => "History of autoimmune hemolytic anemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "699032000",
                "Display" => "History of awareness under general anesthesia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427846005",
                "Display" => "History of B-cell lymphoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "119981000119101",
                "Display" => "History of bacterial infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16093611000119107",
                "Display" => "History of Barrett's esophagus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "69411000119107",
                "Display" => "History of basal cell carcinoma of eyelid",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161473006",
                "Display" => "History of behavior problem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "288391000119107",
                "Display" => "History of being a victim of child physical abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "772993004",
                "Display" => "History of being in foster care",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161769009",
                "Display" => "History of being infant bottle fed",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "134481000119104",
                "Display" => "History of being under immunized",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "288371000119106",
                "Display" => "History of being victim of child abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161483005",
                "Display" => "History of Bell's palsy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "11003051000119102",
                "Display" => "History of benign carcinoid neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59651000119104",
                "Display" => "History of benign carcinoid neoplasm of gastrointestinal tract",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "347941000119102",
                "Display" => "History of benign meningioma of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1099381000119109",
                "Display" => "History of benign neoplasm of bone",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428323002",
                "Display" => "History of benign neoplasm of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "122421000119106",
                "Display" => "History of benign neoplasm of larynx",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10743431000119102",
                "Display" => "History of benign neoplasm of salivary gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15647821000119106",
                "Display" => "History of benign neoplasm of small intestine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "144101000119103",
                "Display" => "History of benign neoplasm of spinal cord",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16094781000119108",
                "Display" => "History of benign phyllodes neoplasm of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "144811000119109",
                "Display" => "History of benign schwannoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.68482E+16",
                "Display" => "History of bilateral lung non-small cell carcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848021000119108",
                "Display" => "History of bilateral primary malignant neoplasm of kidneys",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848261000119105",
                "Display" => "History of bilateral primary malignant neoplasm of lungs",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16847741000119103",
                "Display" => "History of bilateral primary malignant neoplasm of ovaries",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848501000119106",
                "Display" => "History of bilateral primary malignant neoplasm of testes",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848381000119105",
                "Display" => "History of bilateral primary malignant neoplasm of ureters",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161581005",
                "Display" => "History of birth asphyxia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "289917002",
                "Display" => "History of bladder neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "7831000175106",
                "Display" => "History of blood transfusion reaction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "328021000119108",
                "Display" => "History of bowel obstruction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690461000119106",
                "Display" => "History of bradycardia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427902001",
                "Display" => "History of branch retinal vein occlusion",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473123009",
                "Display" => "History of bronchiolitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "126151000119107",
                "Display" => "History of bulimia nervosa",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "12147341000119108",
                "Display" => "History of burn",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429025008",
                "Display" => "History of calculus of kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1099291000119102",
                "Display" => "History of cancer metastatic to bone",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1098971000119104",
                "Display" => "History of cancer metastatic to brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1098951000119108",
                "Display" => "History of cancer metastatic to liver",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1098961000119105",
                "Display" => "History of cancer metastatic to lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1098931000119102",
                "Display" => "History of cancer metastatic to lymph nodes",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1098941000119106",
                "Display" => "History of cancer metastatic to skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73771000119104",
                "Display" => "History of cancer of ampulla of duodenum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88831000119109",
                "Display" => "History of cancer of floor of mouth",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73751000119108",
                "Display" => "History of cancer of gall bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73891000119108",
                "Display" => "History of cancer of unknown primary site",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73801000119102",
                "Display" => "History of cancer of urethra",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "11001921000119108",
                "Display" => "History of carcinoma in situ of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10738811000119103",
                "Display" => "History of carcinoma in situ of uterine cervix",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690651000119106",
                "Display" => "History of carcinoma in situ of vulva",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1204366000",
                "Display" => "History of carcinoma of duodenum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1204445009",
                "Display" => "History of carcinoma of lip",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1204365001",
                "Display" => "History of carcinoma of pancreas",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1204448006",
                "Display" => "History of carcinoma of rectosigmoid junction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1204447001",
                "Display" => "History of carcinoma of stomach",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1186911009",
                "Display" => "History of carcinoma of tongue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "672291000119107",
                "Display" => "History of carcinosarcoma of ovary",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "672281000119109",
                "Display" => "History of carcinosarcoma of uterus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429007001",
                "Display" => "History of cardiac arrest",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "131471000119104",
                "Display" => "History of cardiac arrhythmia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429235008",
                "Display" => "History of cardioembolic stroke",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690491000119104",
                "Display" => "History of cardiomyopathy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789642001",
                "Display" => "History of carotid artery stenosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "144931000119108",
                "Display" => "History of carotid body neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472962008",
                "Display" => "History of cellulitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472966006",
                "Display" => "History of cellulitis of skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10691841000119107",
                "Display" => "History of central serous retinopathy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690051000119100",
                "Display" => "History of cerebellar stroke",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16845421000119109",
                "Display" => "History of cerebral aneurysm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428267002",
                "Display" => "History of cerebral hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275526006",
                "Display" => "History of cerebrovascular accident",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16896891000119106",
                "Display" => "History of cerebrovascular accident due to ischemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "699429007",
                "Display" => "History of cerebrovascular accident in last eight weeks",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "440140008",
                "Display" => "History of cerebrovascular accident with residual deficit",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429993008",
                "Display" => "History of cerebrovascular accident without residual deficits",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "308064009",
                "Display" => "History of cerebrovascular disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16089411000119109",
                "Display" => "History of chest pain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "288411000119107",
                "Display" => "History of child sexual abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "43991000119102",
                "Display" => "History of childhood obesity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472954000",
                "Display" => "History of chlamydial infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73711000119107",
                "Display" => "History of cholangiocarcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16216781000119107",
                "Display" => "History of cholestasis in pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428978004",
                "Display" => "History of choriocarcinoma of placenta",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16098821000119109",
                "Display" => "History of choroiditis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "713031009",
                "Display" => "History of chronic dissection of thoracic aorta",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "414415007",
                "Display" => "History of chronic lung disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "63581000119104",
                "Display" => "History of chronic lymphocytic leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "270473001",
                "Display" => "History of chronic obstructive airway disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472953006",
                "Display" => "History of chronic renal impairment",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441547007",
                "Display" => "History of chronic urinary tract infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "430545008",
                "Display" => "History of claudication",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "97121000119101",
                "Display" => "History of closed head injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16775181000119103",
                "Display" => "History of coma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10995991000119109",
                "Display" => "History of combat and operational stress reaction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429286003",
                "Display" => "History of combination internal cardiac defibrillator and pacemaker",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1156096005",
                "Display" => "History of complication occurring during labor and delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1156097001",
                "Display" => "History of complication of puerperium",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15663121000119104",
                "Display" => "History of concussion injury of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125731000119107",
                "Display" => "History of congenital absence of germinal epithelium of testes",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125771000119105",
                "Display" => "History of congenital adrenal hyperplasia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275529004",
                "Display" => "History of congenital disease of hip",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161577005",
                "Display" => "History of congenital dislocation of hip",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "108281000119102",
                "Display" => "History of congenital hypospadias",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "709046002",
                "Display" => "History of congenital vascular malformation",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161774001",
                "Display" => "History of contraceptive cap usage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "420209000",
                "Display" => "History of contraceptive usage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "681341000119108",
                "Display" => "History of cornea graft failure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15647891000119108",
                "Display" => "History of corneal erosion",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441484007",
                "Display" => "History of craniopharyngioma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "112561000119108",
                "Display" => "History of Crohns disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49071000119104",
                "Display" => "History of Cushing disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49081000119101",
                "Display" => "History of Cushing syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "126731000119100",
                "Display" => "History of cystic dilatation of duct of bulbourethral gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161508001",
                "Display" => "History of deep vein thrombosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "439124004",
                "Display" => "History of deliberate self neglect",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16535771000119101",
                "Display" => "History of delirium",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "32271000119102",
                "Display" => "History of delivery of macrosomal infant",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1260014004",
                "Display" => "History of dental trauma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472970003",
                "Display" => "History of diabetes mellitus type 1",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472969004",
                "Display" => "History of diabetes mellitus type 2",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.09958E+16",
                "Display" => "History of diabetic foot ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "433874008",
                "Display" => "History of diabetic peripheral angiopathy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441638006",
                "Display" => "History of Diamond-Blackfan anemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473107009",
                "Display" => "History of difficult fitting of intrauterine contraceptive device",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427958009",
                "Display" => "History of difficult intubation",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "766035009",
                "Display" => "History of difficult venous access",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472961001",
                "Display" => "History of disorder of connective tissue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "736726000",
                "Display" => "History of disorder of digestive system",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "895699003",
                "Display" => "History of disorder of eye proper",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "895701003",
                "Display" => "History of disorder of eye region",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472956003",
                "Display" => "History of disorder of soft tissue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "180301000119102",
                "Display" => "History of disorder of vision",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690481000119102",
                "Display" => "History of dissection of carotid artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "720810005",
                "Display" => "History of dissection of external carotid artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "720729008",
                "Display" => "History of dissection of internal carotid artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88701000119109",
                "Display" => "History of disseminated malignant neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "118361000119100",
                "Display" => "History of diverticulitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429746005",
                "Display" => "History of domestic abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "720428004",
                "Display" => "History of domestic emotional abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "720411002",
                "Display" => "History of domestic physical abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "293611000119101",
                "Display" => "History of domestic physical abuse of adult",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "720741001",
                "Display" => "History of domestic sexual abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "412732008",
                "Display" => "History of domestic violence",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "371435006",
                "Display" => "History of drug abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "118501000119105",
                "Display" => "History of drug-induced anaphylaxis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88811000119104",
                "Display" => "History of ductal carcinoma in situ of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161783006",
                "Display" => "History of dysmenorrhea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16916101000119104",
                "Display" => "History of dysphagia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472967002",
                "Display" => "History of dysplasia of cervix",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "142471000119105",
                "Display" => "History of dysplasia of vulva",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1099001000119103",
                "Display" => "History of dysthymia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "126131000119101",
                "Display" => "History of eating disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16896851000119101",
                "Display" => "History of embolic cerebrovascular accident",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "141831000119101",
                "Display" => "History of embolic stroke with deficits",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "118971000119107",
                "Display" => "History of embolic stroke without deficits",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15628411000119103",
                "Display" => "History of emergence delirium",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "313217007",
                "Display" => "History of emotional abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428287001",
                "Display" => "History of endocarditis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "130591000119108",
                "Display" => "History of endometrial hyperplasia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "115451000119100",
                "Display" => "History of endophthalmitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73851000119103",
                "Display" => "History of ependymoma of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429010008",
                "Display" => "History of epidural hematoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15912951000119101",
                "Display" => "History of episcleritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16089091000119106",
                "Display" => "History of esophageal ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16093661000119105",
                "Display" => "History of esophageal varices",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "691491000119105",
                "Display" => "History of esophagitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429023001",
                "Display" => "History of Ewings sarcoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "736060002",
                "Display" => "History of exposure to diethylstilbestrol in utero",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "109201000119100",
                "Display" => "History of exposure to fenfluramine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "11018891000119108",
                "Display" => "History of exposure to hazardous bodily fluids",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "442029006",
                "Display" => "History of exposure to lead",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "705133000",
                "Display" => "History of exposure to occupational risk factor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "699009004",
                "Display" => "History of exposure to second hand smoke",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "762296001",
                "Display" => "History of exposure to tobacco smoke in perinatal period",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16880891000119105",
                "Display" => "History of exudative age-related macular degeneration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16880931000119102",
                "Display" => "History of eyelid ptosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16093761000119102",
                "Display" => "History of facial palsy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428942009",
                "Display" => "History of fall",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441586006",
                "Display" => "History of Fanconi anemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125781000119108",
                "Display" => "History of febrile urinary tract infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "715477006",
                "Display" => "History of female genital mutilation",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "763017007",
                "Display" => "History of female infertility due to tubal factor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428064002",
                "Display" => "History of follicular adenocarcinoma of thyroid",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "762616007",
                "Display" => "History of food allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "762615006",
                "Display" => "History of food hypersensitivity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "725942003",
                "Display" => "History of fourth degree perineal laceration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137761000119107",
                "Display" => "History of fracture of hip due to traumatic event",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16300891000119109",
                "Display" => "History of fracture of orbit",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.68295E+16",
                "Display" => "History of gangrenous disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "691501000119103",
                "Display" => "History of gastritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "70861000119106",
                "Display" => "History of gastroesophageal reflux disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275551007",
                "Display" => "History of gastrointestinal bleed",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "140731000119101",
                "Display" => "History of gastrointestinal stromal tumor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16089211000119108",
                "Display" => "History of gastrojejunal ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16279601000119105",
                "Display" => "History of gastroschisis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59601000119103",
                "Display" => "History of germ cell tumor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472971004",
                "Display" => "History of gestational diabetes mellitus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "709881001",
                "Display" => "History of gestational hypertension",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "736725001",
                "Display" => "History of gestational trophoblastic disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "328201000119109",
                "Display" => "History of giant cell arteritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "407553003",
                "Display" => "History of glandular fever",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16775641000119102",
                "Display" => "History of glaucoma suspect",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73841000119100",
                "Display" => "History of glioma of brainstem",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137871000119109",
                "Display" => "History of Graves' disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "128731000119101",
                "Display" => "History of Guillain Barre syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "271902005",
                "Display" => "History of gynecological disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "93931000119108",
                "Display" => "History of Haemophilus influenzae type b infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "425978000",
                "Display" => "History of headache",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428900007",
                "Display" => "History of headache after dural puncture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472955004",
                "Display" => "History of hearing loss",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "117871000119101",
                "Display" => "History of heart block",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "70561000119109",
                "Display" => "History of heartburn",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15627741000119108",
                "Display" => "History of Helicobacter pylori infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161538007",
                "Display" => "History of hematemesis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161550001",
                "Display" => "History of hematuria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "726513006",
                "Display" => "History of hemolysis-elevated liver enzymes-low platelet count syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161458005",
                "Display" => "History of hemolytic anemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161582003",
                "Display" => "History of hemolytic disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428951001",
                "Display" => "History of hemorrhage into ventricle of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "141811000119106",
                "Display" => "History of hemorrhagic cerebrovascular accident with residual deficit",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "118961000119101",
                "Display" => "History of hemorrhagic cerebrovascular accident without residual deficits",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "140701000119108",
                "Display" => "History of hemorrhagic stroke with hemiparesis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "140711000119106",
                "Display" => "History of hemorrhagic stroke with hemiplegia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16851191000119103",
                "Display" => "History of hemorrhoid",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428030001",
                "Display" => "History of hepatitis A",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429721005",
                "Display" => "History of hepatitis B",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59851000119103",
                "Display" => "History of hepatitis B conferring immunity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "93871000119101",
                "Display" => "History of hepatitis C",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49101000119108",
                "Display" => "History of hepatoblastoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49111000119106",
                "Display" => "History of hepatocellular carcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.64755E+16",
                "Display" => "History of herpes simplex keratitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "681221000119108",
                "Display" => "History of herpes zoster",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137741000119108",
                "Display" => "History of hip stress fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49121000119104",
                "Display" => "History of histiocytosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "93911000119103",
                "Display" => "History of histoplasmosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473068004",
                "Display" => "History of Hodgkin lymphoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "309636006",
                "Display" => "History of hospital admission in last year for hyperglycemic disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "293201000119102",
                "Display" => "History of human papilloma virus infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "12618061000119101",
                "Display" => "History of hydronephrosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429406003",
                "Display" => "History of hyperaldosteronism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "414416008",
                "Display" => "History of hypercholesterolemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "97681000119106",
                "Display" => "History of hypercoagulable state",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15627801000119104",
                "Display" => "History of hyphema",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125711000119102",
                "Display" => "History of hypospermatogenesis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472960000",
                "Display" => "History of hypotension",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16235071000119108",
                "Display" => "History of idiopathic intracranial hypertension",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "107921000119107",
                "Display" => "History of immune disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441511006",
                "Display" => "History of immune thrombocytopenia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472973001",
                "Display" => "History of immunodeficiency disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428934008",
                "Display" => "History of inactive tuberculosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "725954003",
                "Display" => "History of induced onset of labor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10624491000119108",
                "Display" => "History of infection due to vancomycin resistant enterococcus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16915501000119102",
                "Display" => "History of infection following procedure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441576005",
                "Display" => "History of infectious disease of central nervous system",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "763016003",
                "Display" => "History of infertility due to endometriosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "608844008",
                "Display" => "History of inflammatory bowel disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789775004",
                "Display" => "History of influenza",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "699010009",
                "Display" => "History of inhalant intoxication",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16535651000119101",
                "Display" => "History of injury of eye region",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "102191000119109",
                "Display" => "History of injury of globe of eye",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16916751000119101",
                "Display" => "History of injury of tendon",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16864911000119103",
                "Display" => "History of intracranial hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16216731000119106",
                "Display" => "History of intrauterine fetal death",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16464661000119103",
                "Display" => "History of intravenous drug abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "48901000119103",
                "Display" => "History of invasive malignant neoplasm of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "350351000119100",
                "Display" => "History of inverted papilloma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427762006",
                "Display" => "History of iron deficiency",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "70871000119100",
                "Display" => "History of irritable bowel syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "141821000119104",
                "Display" => "History of ischemic cerebrovascular accident with residual deficit",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1099621000119102",
                "Display" => "History of ischemic colitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "141281000119101",
                "Display" => "History of ischemic stroke without residual deficits",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161773007",
                "Display" => "History of IUCD (intrauterine contraceptive device) usage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428292004",
                "Display" => "History of Kaposi's sarcoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "99051000119101",
                "Display" => "History of lacunar cerebrovascular accident",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "927778271000119101",
                "Display" => "History of langerhans cell histiocytosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "360679851000119101",
                "Display" => "History of large bowel obstruction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16410191000119105",
                "Display" => "History of laryngospasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161436008",
                "Display" => "History of leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16900351000119101",
                "Display" => "History of lipoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137401000119102",
                "Display" => "History of listeria meningitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "726001007",
                "Display" => "History of live birth",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "143481000119103",
                "Display" => "History of lobular carcinoma in situ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "37251000119108",
                "Display" => "History of low birth weight",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "128131000119102",
                "Display" => "History of low birth weight status, 2-2.5kg",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "128161000119105",
                "Display" => "History of low birth weight status, less than 500 grams",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "116351000119103",
                "Display" => "History of lower extremity skin ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "293771000119100",
                "Display" => "History of lower gastrointestinal bleed",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429423007",
                "Display" => "History of Lyme disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429125006",
                "Display" => "History of lymphadenopathy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473058009",
                "Display" => "History of lymphoid leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "152451000119104",
                "Display" => "History of lymphosarcoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16535401000119102",
                "Display" => "History of macular degeneration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161416007",
                "Display" => "History of malaria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429031006",
                "Display" => "History of male erectile disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "139621000119101",
                "Display" => "History of malignant ameloblastoma of mandible",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428053000",
                "Display" => "History of malignant basal cell neoplasm of skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10995411000119108",
                "Display" => "History of malignant carcinoid tumor of bronchus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "286771000119106",
                "Display" => "History of malignant carcinoid tumor of colon",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10987721000119102",
                "Display" => "History of malignant carcinoid tumor of kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "286791000119107",
                "Display" => "History of malignant carcinoid tumor of rectum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "286781000119109",
                "Display" => "History of malignant carcinoid tumor of small intestine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10987961000119105",
                "Display" => "History of malignant carcinoid tumor of stomach",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10981821000119103",
                "Display" => "History of malignant carcinoid tumor of thymus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "122571000119106",
                "Display" => "History of malignant cutaneous T-cell lymphoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "139281000119104",
                "Display" => "History of malignant germ cell neoplasm of mediastinum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "141951000119104",
                "Display" => "History of malignant germ cell neoplasm of ovary",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "139271000119102",
                "Display" => "History of malignant germ cell neoplasm of testis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "699005005",
                "Display" => "History of malignant hematologic neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429014004",
                "Display" => "History of malignant lymphoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "115561000119102",
                "Display" => "History of malignant melanoma of eye",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "321000119108",
                "Display" => "History of malignant melanoma of the skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "48951000119104",
                "Display" => "History of malignant meningeal neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "347931000119106",
                "Display" => "History of malignant meningioma of meninges of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429144004",
                "Display" => "History of malignant mesothelioma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432007009",
                "Display" => "History of malignant mesothelioma of pleura",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "698298001",
                "Display" => "History of malignant neoplasm of adrenal gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "112071000119105",
                "Display" => "History of malignant neoplasm of anus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73781000119101",
                "Display" => "History of malignant neoplasm of appendix",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415086001",
                "Display" => "History of malignant neoplasm of bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429247002",
                "Display" => "History of malignant neoplasm of bone",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473059001",
                "Display" => "History of malignant neoplasm of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429087003",
                "Display" => "History of malignant neoplasm of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415077006",
                "Display" => "History of malignant neoplasm of bronchus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429484003",
                "Display" => "History of malignant neoplasm of cervix",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429699009",
                "Display" => "History of malignant neoplasm of colon",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "51271000112100",
                "Display" => "History of malignant neoplasm of colon and/or rectum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73791000119103",
                "Display" => "History of malignant neoplasm of common bile duct",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10995641000119109",
                "Display" => "History of malignant neoplasm of digestive organ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "139611000119108",
                "Display" => "History of malignant neoplasm of duodenum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415078001",
                "Display" => "History of malignant neoplasm of ear, nose AND/OR throat",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "431572009",
                "Display" => "History of malignant neoplasm of endocrine gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429089000",
                "Display" => "History of malignant neoplasm of endometrium",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429357003",
                "Display" => "History of malignant neoplasm of epididymis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429410000",
                "Display" => "History of malignant neoplasm of esophagus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10995681000119104",
                "Display" => "History of malignant neoplasm of external ear",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "152891000119106",
                "Display" => "History of malignant neoplasm of eye",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "124361000119101",
                "Display" => "History of malignant neoplasm of fallopian tube",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415079009",
                "Display" => "History of malignant neoplasm of female genital organ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415080007",
                "Display" => "History of malignant neoplasm of gastrointestinal tract",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88851000119103",
                "Display" => "History of malignant neoplasm of gum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "431573004",
                "Display" => "History of malignant neoplasm of head and/or neck",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73721000119100",
                "Display" => "History of malignant neoplasm of hypopharynx",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415081006",
                "Display" => "History of malignant neoplasm of kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473064002",
                "Display" => "History of malignant neoplasm of larynx",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.09399E+16",
                "Display" => "History of malignant neoplasm of lip",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473067009",
                "Display" => "History of malignant neoplasm of liver",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415082004",
                "Display" => "History of malignant neoplasm of lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415083009",
                "Display" => "History of malignant neoplasm of male genital organ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428117004",
                "Display" => "History of malignant neoplasm of mediastinum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10981871000119102",
                "Display" => "History of malignant neoplasm of middle ear",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10981911000119104",
                "Display" => "History of malignant neoplasm of nasal cavity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73731000119102",
                "Display" => "History of malignant neoplasm of nasopharynx",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "434193002",
                "Display" => "History of malignant neoplasm of neck",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "431691000",
                "Display" => "History of malignant neoplasm of nervous system",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473063008",
                "Display" => "History of malignant neoplasm of oral cavity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429090009",
                "Display" => "History of malignant neoplasm of ovary",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73761000119105",
                "Display" => "History of malignant neoplasm of pancreas",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "691371000119102",
                "Display" => "History of malignant neoplasm of parotid gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428880006",
                "Display" => "History of malignant neoplasm of penis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88591000119105",
                "Display" => "History of malignant neoplasm of peritoneum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "85851000119107",
                "Display" => "History of malignant neoplasm of pharynx",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429355006",
                "Display" => "History of malignant neoplasm of pleura",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428262008",
                "Display" => "History of malignant neoplasm of prostate",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429084005",
                "Display" => "History of malignant neoplasm of rectum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "700239008",
                "Display" => "History of malignant neoplasm of renal pelvis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88621000119107",
                "Display" => "History of malignant neoplasm of retroperitoneum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73741000119106",
                "Display" => "History of malignant neoplasm of salivary gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429050006",
                "Display" => "History of malignant neoplasm of skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "443895001",
                "Display" => "History of malignant neoplasm of skin excluding melanoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428833003",
                "Display" => "History of malignant neoplasm of small bowel",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432327003",
                "Display" => "History of malignant neoplasm of spinal cord",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473061005",
                "Display" => "History of malignant neoplasm of stomach",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473066000",
                "Display" => "History of malignant neoplasm of testis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428878000",
                "Display" => "History of malignant neoplasm of thoracic cavity structure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427918007",
                "Display" => "History of malignant neoplasm of thymus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429254008",
                "Display" => "History of malignant neoplasm of thyroid",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429409005",
                "Display" => "History of malignant neoplasm of tongue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88801000119102",
                "Display" => "History of malignant neoplasm of tonsil",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415085002",
                "Display" => "History of malignant neoplasm of trachea",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429444007",
                "Display" => "History of malignant neoplasm of ureter",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415087005",
                "Display" => "History of malignant neoplasm of urinary system",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428944005",
                "Display" => "History of malignant neoplasm of uterine adnexa",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428941002",
                "Display" => "History of malignant neoplasm of uterine body",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427844008",
                "Display" => "History of malignant neoplasm of vagina",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429635001",
                "Display" => "History of malignant neoplasm of vulva",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.60948E+16",
                "Display" => "History of malignant phyllodes neoplasm of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472972006",
                "Display" => "History of maturity onset diabetes mellitus in young",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429016002",
                "Display" => "History of medullary carcinoma of thyroid",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73861000119101",
                "Display" => "History of medulloblastoma of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1251000119106",
                "Display" => "History of melanoma in situ of skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161539004",
                "Display" => "History of melena",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "112101000119101",
                "Display" => "History of meningioma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "138141000119108",
                "Display" => "History of merkel cell carcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "709313000",
                "Display" => "History of methicillin resistant Staphylococcus aureus infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473111003",
                "Display" => "History of microalbuminuria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "608837004",
                "Display" => "History of migraine with aura",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187601005",
                "Display" => "History of military deployment",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16216821000119102",
                "Display" => "History of molar pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1290891000000109",
                "Display" => "History of monkeypox",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427920005",
                "Display" => "History of monocytic leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429124005",
                "Display" => "History of mood disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "120481000119109",
                "Display" => "History of multiple myeloma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16524291000119105",
                "Display" => "History of myalgia caused by statin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "681291000119105",
                "Display" => "History of mycosis fungoides",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "152861000119104",
                "Display" => "History of myeloid leukemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "399211009",
                "Display" => "History of myocardial infarction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "387785661000119105",
                "Display" => "History of myocardial infarction due to atherothrombotic coronary artery disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "776219771000119107",
                "Display" => "History of myocardial infarction due to demand ischemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "461000119108",
                "Display" => "History of myocardial infarction in last eight weeks",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "681211000119101",
                "Display" => "History of myocarditis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1204446005",
                "Display" => "History of nasopharyngeal carcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "82991000119104",
                "Display" => "History of negative cervical Papanicolaou smear performed within last 12 months",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "414372001",
                "Display" => "History of neonatal abstinence syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "11012931000119103",
                "Display" => "History of neoplasm of low malignant potential behavior of ovary",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49201000119104",
                "Display" => "History of neoplasm of pituitary gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10982181000119101",
                "Display" => "History of neoplasm of uncertain behavior",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429105007",
                "Display" => "History of nephroblastoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "90171000119101",
                "Display" => "History of nephrotic syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "32451000119107",
                "Display" => "History of nervous system disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432745001",
                "Display" => "History of neuroblastoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "442414004",
                "Display" => "History of neurodevelopmental disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "134461000119108",
                "Display" => "History of neuroendocrine malignant neoplasm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49191000119102",
                "Display" => "History of neutropenia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "142881000119105",
                "Display" => "History of nocturnal hypoglycemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "85881000119100",
                "Display" => "History of non central nervous system primitive neuroectodermal tumor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428046009",
                "Display" => "History of non-Hodgkins lymphoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429226001",
                "Display" => "History of non-small cell malignant neoplasm of lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "698593009",
                "Display" => "History of non-ST segment elevation myocardial infarction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473057004",
                "Display" => "History of noncompliance with medication regimen",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16890921000119104",
                "Display" => "History of nonproliferative diabetic retinopathy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690071000119109",
                "Display" => "History of nontraumatic ruptured cerebral aneurysm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427956008",
                "Display" => "History of normal menopause",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "715200006",
                "Display" => "History of novel psychoactive substance misuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "80541000119104",
                "Display" => "History of nutritional deficiency",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16535531000119108",
                "Display" => "History of nystagmus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16279641000119107",
                "Display" => "History of occlusion of branch retinal artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432160005",
                "Display" => "History of occlusion of central retinal artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432006000",
                "Display" => "History of occlusion of central retinal vein",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "431310008",
                "Display" => "History of occlusion of cerebral artery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "433807000",
                "Display" => "History of occlusion of cerebral artery without cerebral infarction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "430301009",
                "Display" => "History of occlusive disease of artery of lower extremity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "430305000",
                "Display" => "History of occlusive disease of artery of upper extremity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16540531000119103",
                "Display" => "History of ocular zoster",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1099061000119102",
                "Display" => "History of odontogenic keratocyst",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "106821000119101",
                "Display" => "History of oligodendroglioma of brain",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "97131000119103",
                "Display" => "History of open head injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16535321000119101",
                "Display" => "History of optic atrophy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "13016271000119104",
                "Display" => "History of optic neuritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473108004",
                "Display" => "History of osteomyelitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473442002",
                "Display" => "History of osteopenia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473337006",
                "Display" => "History of osteopenia resolved",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473120007",
                "Display" => "History of osteoporosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473342003",
                "Display" => "History of osteoporosis resolved",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "139461000119109",
                "Display" => "History of osteoporotic fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428052005",
                "Display" => "History of osteosarcoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "934589301000119108",
                "Display" => "History of otitis externa",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427836008",
                "Display" => "History of otitis media",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "142021000119102",
                "Display" => "History of Paget disease of vulva",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "143351000119104",
                "Display" => "History of Paget's disease of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789064006",
                "Display" => "History of pain at rest due to peripheral vascular disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472957007",
                "Display" => "History of pain of multiple joints",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473122004",
                "Display" => "History of palpitations",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "85921000119107",
                "Display" => "History of pancreatitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429255009",
                "Display" => "History of papillary adenocarcinoma of thyroid",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10988171000119101",
                "Display" => "History of parasitic disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "97531000119106",
                "Display" => "History of parietal cerebrovascular accident",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "131461000119105",
                "Display" => "History of paroxysmal atrial tachycardia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "285731000119101",
                "Display" => "History of paroxysmal supraventricular tachycardia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "98241000119103",
                "Display" => "History of partial adherence to treatment",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "123361000119104",
                "Display" => "History of pathological fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137771000119101",
                "Display" => "History of pathological hip fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137751000119105",
                "Display" => "History of pathological vertebral fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "771412001",
                "Display" => "History of pelvic inflammatory disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "293581000119108",
                "Display" => "History of penis injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "350391000119105",
                "Display" => "History of perforated tympanic membrane",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "285741000119105",
                "Display" => "History of pericarditis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161584002",
                "Display" => "History of perinatal cerebral irritability",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15758941000119102",
                "Display" => "History of perineal laceration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "431545001",
                "Display" => "History of peripheral arterial occlusive disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789544005",
                "Display" => "History of peripheral ischemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473109007",
                "Display" => "History of peripheral vascular disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "439589003",
                "Display" => "History of peritonsillar abscess",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49221000119108",
                "Display" => "History of pheochromocytoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "313215004",
                "Display" => "History of physical abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "691191000119103",
                "Display" => "History of pituitary adenoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "725950007",
                "Display" => "History of placenta accreta",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "725924008",
                "Display" => "History of placenta previa",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789776003",
                "Display" => "History of placental abruption",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1881000119103",
                "Display" => "History of plasmacytoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10743471000119104",
                "Display" => "History of pleomorphic adenoma of salivary gland",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "700236001",
                "Display" => "History of pleural effusion",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "93961000119100",
                "Display" => "History of pneumococcal infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473113000",
                "Display" => "History of polymyalgia rheumatica",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428283002",
                "Display" => "History of polyp of colon",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "735934008",
                "Display" => "History of poor personal hygiene",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59701000119109",
                "Display" => "History of portal hypertension",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428608003",
                "Display" => "History of positive Toxoplasma gondii antibody",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16409881000119106",
                "Display" => "History of posterior ischemic optic neuropathy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16615741000119105",
                "Display" => "History of posterior vitreous detachment",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16916061000119102",
                "Display" => "History of postpartum gestational hypertension",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161809000",
                "Display" => "History of postpartum hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "105651000119100",
                "Display" => "History of pre-eclampsia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15011000119104",
                "Display" => "History of pregnancy loss in non-pregnant woman",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1156099003",
                "Display" => "History of pregnancy with abnormal glucose tolerance test",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "713651007",
                "Display" => "History of pregnancy with abortive outcome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1156101005",
                "Display" => "History of pregnancy with normal glucose tolerance test",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441493008",
                "Display" => "History of premature labor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "138091000119101",
                "Display" => "History of prematurity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "421711004",
                "Display" => "History of pressure injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "108951000119100",
                "Display" => "History of preterm premature rupture of membranes",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "789395008",
                "Display" => "History of primary cutaneous lymphoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49231000119106",
                "Display" => "History of primary hyperparathyroidism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848701000119101",
                "Display" => "History of primary malignant neoplasm of anterior wall of urinary bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16894981000119106",
                "Display" => "History of primary malignant neoplasm of base of tongue",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848781000119109",
                "Display" => "History of primary malignant neoplasm of dome of urinary bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1321000119109",
                "Display" => "History of primary malignant neoplasm of larynx",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848901000119103",
                "Display" => "History of primary malignant neoplasm of lateral wall of urinary bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16847981000119105",
                "Display" => "History of primary malignant neoplasm of left kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848061000119103",
                "Display" => "History of primary malignant neoplasm of left lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.68477E+16",
                "Display" => "History of primary malignant neoplasm of left ovary",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848421000119101",
                "Display" => "History of primary malignant neoplasm of left testis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.68483E+16",
                "Display" => "History of primary malignant neoplasm of left ureter",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848941000119101",
                "Display" => "History of primary malignant neoplasm of neck of urinary bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1311000119102",
                "Display" => "History of primary malignant neoplasm of oropharynx",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848861000119109",
                "Display" => "History of primary malignant neoplasm of posterior wall of urinary bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16847821000119103",
                "Display" => "History of primary malignant neoplasm of right kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.68481E+16",
                "Display" => "History of primary malignant neoplasm of right lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16847781000119108",
                "Display" => "History of primary malignant neoplasm of right ovary",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848461000119106",
                "Display" => "History of primary malignant neoplasm of right testis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848301000119102",
                "Display" => "History of primary malignant neoplasm of right ureter",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "765378003",
                "Display" => "History of primary malignant neoplasm of skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1301000119100",
                "Display" => "History of primary malignant neoplasm of testis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.6849E+16",
                "Display" => "History of primary malignant neoplasm of trigone of urinary bladder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848821000119104",
                "Display" => "History of primary malignant neoplasm of urachus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848741000119104",
                "Display" => "History of primary malignant neoplasm of ureteric orifice",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848181000119108",
                "Display" => "History of primary non-small cell carcinoma of left lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16848141000119103",
                "Display" => "History of primary non-small cell carcinoma of right lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "619792361000119102",
                "Display" => "History of primary small cell carcinoma of lung",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.68479E+16",
                "Display" => "History of primary transitional cell carcinoma of left kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16847901000119102",
                "Display" => "History of primary transitional cell carcinoma of right kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "699029003",
                "Display" => "History of primitive neuroectodermal tumor",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49241000119102",
                "Display" => "History of prolactinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16890881000119106",
                "Display" => "History of proliferative retinopathy due to diabetes mellitus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125791000119106",
                "Display" => "History of prostatic cyst",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10988211000119104",
                "Display" => "History of prostatic dysplasia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "681201000119104",
                "Display" => "History of prostatitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473110002",
                "Display" => "History of proteinuria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59711000119107",
                "Display" => "History of pseudoaneurysm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10761511000119101",
                "Display" => "History of pulmonary embolism on long-term anticoagulation therapy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16089131000119108",
                "Display" => "History of pyloric channel ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473089008",
                "Display" => "History of recent air travel",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473087005",
                "Display" => "History of recent cruise travel",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16885661000119108",
                "Display" => "History of rectal bleeding",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16089171000119106",
                "Display" => "History of rectal ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "10987871000119109",
                "Display" => "History of rectosigmoid junction cancer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "141911000119100",
                "Display" => "History of recurrent deep vein thrombosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429716007",
                "Display" => "History of recurrent dislocation of hip joint prosthesis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473062003",
                "Display" => "History of recurrent malignant neoplasm of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "699015004",
                "Display" => "History of recurrent pneumonia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "439142009",
                "Display" => "History of recurrent tonsillitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473116008",
                "Display" => "History of recurrent urinary tract infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161792009",
                "Display" => "History of recurrent vaginal discharge",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "414417004",
                "Display" => "History of renal failure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73901000119107",
                "Display" => "History of renal insufficiency",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "725948004",
                "Display" => "History of retained placenta",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "108521000119103",
                "Display" => "History of reticulosarcoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "13016311000119104",
                "Display" => "History of retinal edema",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1.65354E+16",
                "Display" => "History of retinal hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16535611000119102",
                "Display" => "History of retinitis pigmentosa",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "97641000119101",
                "Display" => "History of retinoblastoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16519911000119101",
                "Display" => "History of retinopathy of prematurity",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16524331000119104",
                "Display" => "History of rhabdomyolysis due to statin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "73881000119105",
                "Display" => "History of rhabdomyosarcoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428309004",
                "Display" => "History of RhD negative",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1156098006",
                "Display" => "History of Rhesus isoimmunization affecting pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16916791000119106",
                "Display" => "History of rupture of Achilles tendon",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "292508471000119105",
                "Display" => "History of SARS-CoV-2",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16475431000119103",
                "Display" => "History of Schatzkis ring",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "138741000119107",
                "Display" => "History of sebaceous adenoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "138731000119103",
                "Display" => "History of sebaceous carcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "725943008",
                "Display" => "History of second degree perineal laceration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "136611000119100",
                "Display" => "History of sepsis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "112031000119107",
                "Display" => "History of severe nausea and vomiting following administration of anesthetic agent",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "141961000119102",
                "Display" => "History of sex cord stromal tumor of ovary",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "313216003",
                "Display" => "History of sexual abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "34441000087108",
                "Display" => "History of sexual behavior with high risk of exposure to communicable disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "275881005",
                "Display" => "History of sexually transmitted disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1208622001",
                "Display" => "History of short umbilical cord",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "816150000",
                "Display" => "History of shoulder dystocia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "117881000119103",
                "Display" => "History of sick sinus syndrome",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441482006",
                "Display" => "History of sickle cell anemia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "703151001",
                "Display" => "History of single seizure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "152711000119105",
                "Display" => "History of skin and/or subcutaneous tissue disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "420697001",
                "Display" => "History of skin ulcer",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "772995006",
                "Display" => "History of sleeping out",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "311030311000119106",
                "Display" => "History of small bowel obstruction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59801000119102",
                "Display" => "History of small vessel disease due to diabetes mellitus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49291000119105",
                "Display" => "History of soft tissue sarcoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "432220003",
                "Display" => "History of spinal cord infarct",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "60681000119105",
                "Display" => "History of spinal cord injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441494002",
                "Display" => "History of splenomegaly",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "69551000119109",
                "Display" => "History of squamous cell carcinoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "97631000119105",
                "Display" => "History of squamous cell carcinoma in situ",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429024007",
                "Display" => "History of squamous cell carcinoma of skin",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "430168009",
                "Display" => "History of stable aneurysm of abdominal aorta",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16535691000119106",
                "Display" => "History of strabismus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "123351000119101",
                "Display" => "History of stress fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16915451000119104",
                "Display" => "History of stricture of esophagus",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "143341000119101",
                "Display" => "History of stromal sarcoma of endometrium",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161515009",
                "Display" => "History of subarachnoid hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428275008",
                "Display" => "History of subdural hematoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "371422002",
                "Display" => "History of substance abuse",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1254976004",
                "Display" => "History of substance dependency",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "36511000119106",
                "Display" => "History of suspected exposure to biological agent",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428340006",
                "Display" => "History of sustained ventricular fibrillation",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429046004",
                "Display" => "History of sustained ventricular tachycardia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "674191000119109",
                "Display" => "History of syncope",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1087151000119108",
                "Display" => "History of syphilis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428984001",
                "Display" => "History of tear of retina",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "427040000",
                "Display" => "History of testicular disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "725941005",
                "Display" => "History of third degree perineal laceration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59721000119100",
                "Display" => "History of thoracoabdominal aortic aneurysm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "439627005",
                "Display" => "History of threatening violent behavior toward others",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "49341000119108",
                "Display" => "History of thrombocytopenia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428904003",
                "Display" => "History of thromboembolism of vein",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "441882000",
                "Display" => "History of thrombophilia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "430080003",
                "Display" => "History of thrombophlebitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "118951000119103",
                "Display" => "History of thrombotic stroke without residual deficits",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "85931000119105",
                "Display" => "History of tobacco use in remission less than 12 months",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472958002",
                "Display" => "History of tonsillitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "690731000119108",
                "Display" => "History of torsades de pointe caused by drug",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1098861000119103",
                "Display" => "History of torsades type ventricular tachycardia due to prolonged QT interval",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "109211000119102",
                "Display" => "History of toxic inhalation exposure",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "143591000119101",
                "Display" => "History of toxoplasmosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59641000119101",
                "Display" => "History of tracheoesophageal fistula",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161511000",
                "Display" => "History of transient ischemic attack",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "140221000119109",
                "Display" => "History of transient ischemic attack due to embolism",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "140491000119105",
                "Display" => "History of transitional cell carcinoma of kidney",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "88611000119100",
                "Display" => "History of traumatic brain injury",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137731000119104",
                "Display" => "History of traumatic vertebral fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "34451000087106",
                "Display" => "History of travel with high risk of exposure to communicable disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "94001000119104",
                "Display" => "History of typhoid",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "99021000119109",
                "Display" => "History of undescended testes",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "312489007",
                "Display" => "History of upper gastrointestinal tract hemorrhage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137961000119105",
                "Display" => "History of ureteral obstruction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125701000119100",
                "Display" => "History of urethral cyst",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125981000119105",
                "Display" => "History of urethral parameatal cyst",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "125961000119101",
                "Display" => "History of urethrocutaneous fistula",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1201000119107",
                "Display" => "History of urinary tract infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "11012891000119106",
                "Display" => "History of urticaria",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473093002",
                "Display" => "History of use of depot contraception",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473099003",
                "Display" => "History of use of hormone releasing intrauterine device contraception",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473103008",
                "Display" => "History of use of postcoital contraception",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473102003",
                "Display" => "History of use of progestogen only oral contraceptive",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473096005",
                "Display" => "History of use of symptothermal method of contraception",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473097001",
                "Display" => "History of use of withdrawal method of contraception",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "783680006",
                "Display" => "History of uterine laceration",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16098861000119104",
                "Display" => "History of uveitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "142461000119104",
                "Display" => "History of vaginal intraepithelial neoplasia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "472974007",
                "Display" => "History of vasculitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1237120008",
                "Display" => "History of venous ulcer of lower leg",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1237121007",
                "Display" => "History of venous ulcer of lower limb",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473118009",
                "Display" => "History of ventricular septal defect",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "137721000119102",
                "Display" => "History of vertebral stress fracture",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429032004",
                "Display" => "History of vesicoureteric reflux",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "15647781000119101",
                "Display" => "History of villous adenoma of duodenum",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "440234007",
                "Display" => "History of violent behavior toward others",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473117004",
                "Display" => "History of viral hepatitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "59741000119106",
                "Display" => "History of visceral aneurysm",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428863007",
                "Display" => "History of vitreous floater",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16468501000119108",
                "Display" => "History of Zika virus infection",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161637005",
                "Display" => "Insurance refused - medical reasons",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187610002",
                "Display" => "Left military service",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "445585003",
                "Display" => "Livebirth born before admission to hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "442311008",
                "Display" => "Liveborn born in hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "442365008",
                "Display" => "Liveborn born in hospital by cesarean section",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187609007",
                "Display" => "Medically discharged from military service",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "394989007",
                "Display" => "Missed childhood immunizations",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "315604002",
                "Display" => "Missed contraceptive pill",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16089691000119102",
                "Display" => "Multiple liveborn in hospital by vaginal delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "717807001",
                "Display" => "Multiple liveborn other than twins born in hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "717808006",
                "Display" => "Multiple liveborn other than twins born outside hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473386008",
                "Display" => "Never used condom",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473387004",
                "Display" => "Never used contraception",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "310596004",
                "Display" => "No H/O=>Glaucoma",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "310595000",
                "Display" => "No H/O=>Iritis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "160254003",
                "Display" => "No history of cardiovascular system disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "160257005",
                "Display" => "No history of central nervous system disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "704006006",
                "Display" => "No history of cutaneous cellulitis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "700143000",
                "Display" => "No history of depression",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473074004",
                "Display" => "No history of dizziness",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "438760008",
                "Display" => "No history of eclampsia",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "700145007",
                "Display" => "No history of ectopic pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "160255002",
                "Display" => "No history of gastrointestinal tract disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "160256001",
                "Display" => "No history of genitourinary tract disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "473073005",
                "Display" => "No history of hearing loss",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161755009",
                "Display" => "No history of induced termination of pregnancy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "700363003",
                "Display" => "No history of malignant neoplastic disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "700146008",
                "Display" => "No history of malignant tumor of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "394707001",
                "Display" => "No history of migraine",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "161745005",
                "Display" => "No history of miscarriage",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "700144006",
                "Display" => "No history of ovarian cyst",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "160259008",
                "Display" => "No history of psychiatric disorder",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "160258000",
                "Display" => "No history of respiratory system disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "704007002",
                "Display" => "No history of sexually transmitted infectious disease",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "716186003",
                "Display" => "No known allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "716220001",
                "Display" => "No known animal allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "409137002",
                "Display" => "No known drug allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428607008",
                "Display" => "No known environmental allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "429625007",
                "Display" => "No known food allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1003774007",
                "Display" => "No known Hevea brasiliensis latex allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428197003",
                "Display" => "No known insect allergy",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "413076004",
                "Display" => "No past history of venous thrombosis",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "160253009",
                "Display" => "No significant social history",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "726565008",
                "Display" => "Past history of small for gestational age baby",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "703154009",
                "Display" => "Patient reports no current disability",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "415076002",
                "Display" => "Personal history of primary malignant neoplasm of breast",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "433161004",
                "Display" => "Recent injury of posterior cruciate ligament",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "428752002",
                "Display" => "Recent myocardial infarction",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "698811001",
                "Display" => "Recent rupture of posterior cruciate ligament",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "705002000",
                "Display" => "Repeated self-induced vomiting",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187613000",
                "Display" => "Resigned military service commission",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187600006",
                "Display" => "Served in military service",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "1187606000",
                "Display" => "Served in military service during peacetime",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "442423001",
                "Display" => "Single liveborn born in hospital by cesarean section",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "17561000119102",
                "Display" => "Single liveborn born in hospital by vaginal delivery",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "717803002",
                "Display" => "Singleton liveborn born in hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "717804008",
                "Display" => "Singleton liveborn born outside hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "717805009",
                "Display" => "Singleton liveborn unspecified as to place of birth",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "16089571000119102",
                "Display" => "Triplet liveborn in hospital by cesarean section",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "442327001",
                "Display" => "Twin liveborn born in hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "442342003",
                "Display" => "Twin liveborn born in hospital by cesarean section",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT "
            ],
            [
                "Code" => "717806005",
                "Display" => "Twin liveborn born outside hospital",
                "Code System" => "http://snomed.info/sct",
                "Keterangan" => "SNOMED-CT"
            ]
        ];

        foreach ($datas2 as $item) {
            MasterConsultationSnomedCT::updateOrCreate([
                'code' => $item['Code'],
            ], [
                'display' => $item['Display'],
                'code_system' => $item['Code System'],
                'keterangan' => $item['Keterangan'],
                'type' => 'riwayat-penyakit',
            ]);
        }
    }
}
