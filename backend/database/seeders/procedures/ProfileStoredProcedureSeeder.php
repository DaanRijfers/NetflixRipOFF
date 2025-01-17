<?php

namespace Database\Seeders\Procedures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileStoredProcedureSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS GetAllProfiles;
            CREATE PROCEDURE GetAllProfiles()
            BEGIN
                SELECT * FROM profiles;
            END;

            DROP PROCEDURE IF EXISTS GetProfileById;
            CREATE PROCEDURE GetProfileById(IN profileId INT)
            BEGIN
                SELECT * FROM profiles WHERE id = profileId;
            END;

            DROP PROCEDURE IF EXISTS CreateProfile;
            CREATE PROCEDURE CreateProfile(IN userId INT, IN name VARCHAR(255), IN favoriteAnimal VARCHAR(255), IN mediaPreference VARCHAR(255), IN languageId INT, IN profilePicture LONGBLOB)
            BEGIN
                INSERT INTO profiles (user_id, name, favorite_animal, media_preference, language_id, profile_picture) VALUES (userId, name, favoriteAnimal, mediaPreference, languageId, profilePicture);
            END;

            DROP PROCEDURE IF EXISTS UpdateProfile;
            CREATE PROCEDURE UpdateProfile(IN profileId INT, IN profileData JSON)
            BEGIN
                UPDATE profiles SET name = JSON_UNQUOTE(JSON_EXTRACT(profileData, "$.name")), favorite_animal = JSON_UNQUOTE(JSON_EXTRACT(profileData, "$.favorite_animal")), media_preference = JSON_UNQUOTE(JSON_EXTRACT(profileData, "$.media_preference")), language_id = JSON_UNQUOTE(JSON_EXTRACT(profileData, "$.language_id")), profile_picture = JSON_UNQUOTE(JSON_EXTRACT(profileData, "$.profile_picture")) WHERE id = profileId;
            END;

            DROP PROCEDURE IF EXISTS DeleteProfile;
            CREATE PROCEDURE DeleteProfile(IN profileId INT)
            BEGIN
                DELETE FROM profiles WHERE id = profileId;
            END;

            DROP PROCEDURE IF EXISTS GetFavoriteContentByUserId;
            CREATE PROCEDURE GetFavoriteContentByUserId(IN userId INT)
            BEGIN
                SELECT * FROM favorite_content WHERE user_id = userId;
            END;
        ');
    }
}