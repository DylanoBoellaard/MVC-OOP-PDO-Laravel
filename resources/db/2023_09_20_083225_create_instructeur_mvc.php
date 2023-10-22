<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step: 02 - Create a new table instructeur
        Schema::dropIfExists('instructeurs');
        Schema::create('instructeurs', function (Blueprint $table) {
            $table->id();
            $table->string('voornaam', 50);
            $table->string('tussenvoegsel', 10);
            $table->string('achternaam', 50);
            $table->string('mobiel', 12);
            $table->date('datumInDienst');
            $table->string('aantalSterren', 6);
            $table->boolean('isActief')->default(true);
            $table->string('opmerkingen', 250)->nullable();
            $table->timestamps(6);
            $table->engine = 'InnoDB';

            //$table->primary('Id');
        });

        // Step: 03 - Fill table Instructeur with data
        DB::table('instructeurs')->insert([
            [
                'voornaam' => 'Li',
                'tussenvoegsel' => '',
                'achternaam' => 'Zhan',
                'mobiel' => '06-28493827',
                'datumInDienst' => '2015-04-17',
                'aantalSterren' => '***',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voornaam' => 'Leroy',
                'tussenvoegsel' => '',
                'achternaam' => 'Boerhaven',
                'mobiel' => '06-39398734',
                'datumInDienst' => '2018-06-25',
                'aantalSterren' => '*',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voornaam' => 'Yoeri',
                'tussenvoegsel' => 'Van',
                'achternaam' => 'Veen',
                'mobiel' => '06-24383291',
                'datumInDienst' => '2010-05-12',
                'aantalSterren' => '***',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voornaam' => 'Bert',
                'tussenvoegsel' => 'Van',
                'achternaam' => 'Sali',
                'mobiel' => '06-48293823',
                'datumInDienst' => '2023-01-10',
                'aantalSterren' => '****',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voornaam' => 'Mohammed',
                'tussenvoegsel' => 'El',
                'achternaam' => 'Yasssidi',
                'mobiel' => '06-34291234',
                'datumInDienst' => '2010-06-14',
                'aantalSterren' => '*****',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ]
        ]);

        // Step: 03 - Create a new table typeVoertuig
        Schema::dropIfExists('typeVoertuigs');
        Schema::create('typeVoertuigs', function (Blueprint $table) {
            $table->id();
            $table->string('typeVoertuig', 50);
            $table->string('rijbewijsCategorie', 10);
            $table->boolean('isActief')->default(true);
            $table->string('opmerkingen', 250)->nullable();
            $table->timestamps(6);
            $table->engine = 'InnoDB';

            //$table->primary('Id');
        });

        // Step: 04 - Fill table typeVoertuig with data
        DB::table('typeVoertuigs')->insert([
            [
                'typeVoertuig' => 'Personenauto',
                'rijbewijsCategorie' => 'B',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'typeVoertuig' => 'Vrachtwagen',
                'rijbewijsCategorie' => 'C',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'typeVoertuig' => 'Bus',
                'rijbewijsCategorie' => 'D',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'typeVoertuig' => 'Bromfiets',
                'rijbewijsCategorie' => 'AM',
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ]
        ]);

        // Step: 05 - Create a new table voertuig
        Schema::dropIfExists('voertuigs');
        Schema::create('voertuigs', function (Blueprint $table) {
            $table->id();
            $table->string('kenteken', 50);
            $table->string('type', 50);
            $table->date('bouwjaar');
            $table->string('brandstof', 12);
            $table->foreignId('typeVoertuigsId') // Declare foreign key
                ->references('id')
                ->on('typevoertuigs')
                ->onDelete('cascade'); // Cascade = if record in referenced table gets deleted, all related records in the current table will also be deleted
            $table->boolean('isActief')->default(true);
            $table->string('opmerkingen', 250)->nullable();
            $table->timestamps(6);
            $table->engine = 'InnoDB';
        });

        // Step: 06 - Fill table voertuig with data
        DB::table('voertuigs')->insert([
            [
                'kenteken' => 'AU-67-IO',
                'type' => 'Golf',
                'bouwjaar' => '2017-06-12',
                'brandstof' => 'Diesel',
                'typeVoertuigsId' => 1,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => 'TR-24-OP',
                'type' => 'DAF',
                'bouwjaar' => '2019-05-23',
                'brandstof' => 'Diesel',
                'typeVoertuigsId' => 2,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => 'TH-78-KL',
                'type' => 'Mercedes',
                'bouwjaar' => '2023-01-01',
                'brandstof' => 'Benzine',
                'typeVoertuigsId' => 1,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => '90-KL-TR',
                'type' => 'Fiat 500',
                'bouwjaar' => '2021-09-12',
                'brandstof' => 'Benzine',
                'typeVoertuigsId' => 1,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => '34-TK-LP',
                'type' => 'Scania',
                'bouwjaar' => '2015-03-13',
                'brandstof' => 'Diesel',
                'typeVoertuigsId' => 2,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => 'YY-OP-78',
                'type' => 'BMW M5',
                'bouwjaar' => '2022-05-13',
                'brandstof' => 'Diesel',
                'typeVoertuigsId' => 1,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => 'UU-HH-JK',
                'type' => 'M.A.N.',
                'bouwjaar' => '2017-12-03',
                'brandstof' => 'Diesel',
                'typeVoertuigsId' => 2,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => 'ST-FZ-28',
                'type' => 'Citroën',
                'bouwjaar' => '2018-01-20',
                'brandstof' => 'Elektrisch',
                'typeVoertuigsId' => 1,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => '123-FR-T',
                'type' => 'Piaggio ZIP',
                'bouwjaar' => '2021-02-01',
                'brandstof' => 'Benzine',
                'typeVoertuigsId' => 4,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => 'DRS-52-P',
                'type' => 'Vespa',
                'bouwjaar' => '2022-03-21',
                'brandstof' => 'Benzine',
                'typeVoertuigsId' => 4,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => 'STP-12-U',
                'type' => 'Kymco',
                'bouwjaar' => '2022-07-02',
                'brandstof' => 'Benzine',
                'typeVoertuigsId' => 4,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'kenteken' => '45-SD-23',
                'type' => 'Renault',
                'bouwjaar' => '2023-01-01',
                'brandstof' => 'Diesel',
                'typeVoertuigsId' => 3,
                'isActief' => 1,
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ]
        ]);

        // Step: 07 - Create a new table voertuig
        Schema::dropIfExists('voertuigInstructeurs');
        Schema::create('voertuigInstructeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voertuigsId') // Declare foreign key
                ->references('id')
                ->on('voertuigs')
                ->onDelete('cascade');

            $table->foreignId('instructeursId') // Declare foreign key
                ->references('id')
                ->on('instructeurs')
                ->onDelete('cascade'); // Cascade = if record in referenced table gets deleted, all related records in the current table will also be deleted
            $table->date('datumToekenning')->default(now());
            $table->boolean('isActief')->default(true);
            $table->string('opmerkingen', 250)->nullable();
            $table->timestamps(6);
            $table->engine = 'InnoDB';
        });

        DB::table('voertuigInstructeurs')->insert([
            [
                'voertuigsId' => 1,
                'instructeursId' => 5,
                'datumToekenning' => '2017-06-18',
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voertuigsId' => 3,
                'instructeursId' => 1,
                'datumToekenning' => '2021-09-26',
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voertuigsId' => 9,
                'instructeursId' => 1,
                'datumToekenning' => '2021-09-27',
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voertuigsId' => 4,
                'instructeursId' => 4,
                'datumToekenning' => '2022-08-01',
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voertuigsId' => 5,
                'instructeursId' => 1,
                'datumToekenning' => '2019-08-30',
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ],
            [
                'voertuigsId' => 10,
                'instructeursId' => 5,
                'datumToekenning' => '2020-02-02',
                'opmerkingen' => null,
                'created_at' => now()->micro(6),
                'updated_at' => now()->micro(6),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructeurs');
        Schema::dropIfExists('typeVoertuigs');
        Schema::dropIfExists('voertuigs');
        Schema::dropIfExists('voertuigInstructeurs');
    }
};
