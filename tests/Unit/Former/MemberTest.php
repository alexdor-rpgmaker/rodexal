<?php

namespace Tests\Unit\Former;

use App\Former\Member;

use Tests\TestCase;

/**
 * @testdox Member
 */
class MemberTest extends TestCase
{
    /**
     * @test
     * @param int    $rank
     * @param string $expected
     * @testdox Rank - If rank is $rank, returns $expected
     * Si le rang est $rank, renvoie $expected
     * @testWith        [0, "guest"]
     *                  [1, "member"]
     *                  [2, "challenger"]
     *                  [4, "juror"]
     *                  [6, "administrator"]
     */
    public function rankJuror($rank, $expected)
    {
        $member = Member::factory()->make([
            'rang' => $rank,
        ]);

        $this->assertEquals($expected, $member->rank);
    }

    /**
     * @test
     * @testdox getLink - Returns the link of the user
     * Renvoie le lien du membre
     */
    public function getLink()
    {
        $member = Member::factory()->make([
            'id_membre' => 3,
            'pseudo' => 'Juan-Pablo',
            'rang' => 4
        ]);

        $actualHtml = $member->getLink();

        $expectedHtml = <<<html
<a href="https://alex-dor.test/?p=profil&membre=3" class="color-juror">Juan-Pablo</a>
html;

        $this->assertEquals($expectedHtml, $actualHtml);
    }
}
