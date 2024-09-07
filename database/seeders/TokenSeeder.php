<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TokenSeeder extends Seeder
{
    public function run()
    {
        // Insert data into the tokens table
        DB::table('tokens')->insert([
            'id' => 1,
            'service' => 'qbo',
            'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..q0802tX2gL5JcOD4uIH1Pw.rf7kXWJICIZ6hoxjU2MwgQcwu8i6dVhTITQoKpx8d9P4B_DexrvbpT7SDdqbBSY0yyl4W3WApQnD_Sk8k1P3N2_446vxej_qgyXXVNtKsb5VOmH48rZgvOOn9t5krj713eNQzXImFKKkUWVS2qSZnQr7kL15sJQdW1CwbBOVvFrany3tyFvNc5JopMJV3YTM_YomR95WdrBcV6jwTSKVkRJQ4dNHFQ8qMsiGynI9R8bBmTgQ5BDzM4OwxK-aSLr65FWlGuVP_RraQhVIvy8-UHpZKJ5qBgDjrj9j06dcnAieh4ew1OUtj6doCsca0JSKHoPN-ORNEZPfh_Cgu13kgBt2eBYatc2WxQhaJycuqwVnKnDkQO15VL_96ck-g5FwoHQe8HM9IHQYPs4P50w0qIlTB8fdlRKHBTBddajWNp6lSrDnGV4rZzLpyHB0dVHCmDaIy264SZ6g1jyPGnKQmNLnGhwh025r3rO_7sjQksvuFIIjbBEmFeFYdQMgB4iOrMl0RTRaP2b_r4UjTHXFxenclS9LsIQyCFzP5dGTJxxLUsUCxiZNYTiPGEuaFXrcnJ-DwTLntI5ZIu_lbPjFgkAGKtpvnwFid48kZbyYklDF9x8JOXAd-gDRexBDsE6TGFtiPkdh6CeHR-CvBdMUqaXHRxiw7OmzHTlK59wOuslVxTCRN-AQ6wLDsl3YEbT_Bcaoy25bXIdLjFANvmEL1i-UhUfg6yzun0vwbC9QNEI.hU3CknqNWTdCxUB-o7_YNQ',
            'refresh_token' => 'AB11734428474jGUBAAgyf8nmg369Z8cxiluydhbNkf7x0aYi2',
            'realm_id' => '9341453052111903',
            'created_at' => '2024-09-07 01:26:19',
            'updated_at' => '2024-09-07 09:25:51',
        ]);
    }
}
