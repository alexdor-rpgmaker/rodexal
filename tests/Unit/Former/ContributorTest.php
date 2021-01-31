<?php

namespace Tests\Unit\Former;

use App\Former\Member;
use App\Former\Contributor;

use Tests\TestCase;

/**
 * @testdox Contributor
 */
class ContributorTest extends TestCase
{
    /**
     * @test
     * @testdox getLinkOrNameAttribute - If there is a member, returns the link of the member
     * S'il y a un membre, renvoie le lien du membre
     */
    public function linkOrName_ifThereIsAMember()
    {
        $membre = $this->partialMock(
            Member::class,
            fn ($mock) => $mock->shouldReceive('getLink')
                ->andReturn('<a href="jp">Juan-Pablo</a>')
        );
        $contributor = $this->partialMock(
            Contributor::class,
            fn ($mock) => $mock->shouldReceive('getAttribute')
                ->with('member')
                ->andReturn($membre)
        );

        $actualHtml = $contributor->linkOrName;

        $expectedHtml = '<a href="jp">Juan-Pablo</a>';
        $this->assertEquals($expectedHtml, $actualHtml);
    }

    /**
     * @test
     * @testdox getLinkOrNameAttribute - If there is no member, returns the contributor name attribute
     * S'il n'y a pas de membre, renvoie l'attribut nom du participant
     */
    public function linkOrName_ifThereIsNoMember()
    {
        $contributor = Contributor::factory()->make([
            'nom_membre' => 'Juanita',
        ]);

        $actualHtml = $contributor->linkOrName;

        $expectedHtml = 'Juanita';
        $this->assertEquals($expectedHtml, $actualHtml);
    }
}
