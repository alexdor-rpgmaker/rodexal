<?php

namespace Tests\Unit\Helpers;

use BBCode;
use Tests\TestCase;

/**
 * @testdox BBCode
 */
class BBCodeTest extends TestCase
{
    protected $bbCode;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bbCode = BBCode::construireParserBBCode();
    }

    /**
     * @testdox Le BBCode [b] utilise la balise html strong
     */
    public function testBBCodeBUtiliseStrong()
    {
        $texteAvecBbcode = "[b]Du gras[/b]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<strong>Du gras</strong>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [g] utilise la balise html strong
     */
    public function testBBCodeGUtiliseStrong()
    {
        $texteAvecBbcode = "[g]Du gras[/g]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<strong>Du gras</strong>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [i] utilise la balise html em
     */
    public function testBBCodeIUtiliseEm()
    {
        $texteAvecBbcode = "[i]De l'italique[/i]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<em>De l'italique</em>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [strike] utilise la balise html s
     */
    public function testBBCodeSUtiliseS()
    {
        $texteAvecBbcode = "[s]Ceci est barré[/s]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<s>Ceci est barré</s>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [strike] utilise la balise html s
     */
    public function testBBCodeStrikeUtiliseS()
    {
        $texteAvecBbcode = "[strike]Ceci est barré[/strike]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<s>Ceci est barré</s>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [img-2x] utilise la balise html img
     */
    public function testBBCodeImg2XUtiliseImg()
    {
        $texteAvecBbcode = "[img-2x]https://t.co/super-image.jpg[/img-2x]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<img style=\"width: 50%;\" src=\"https://t.co/super-image.jpg\">";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [list=1] crée une liste ordonnée
     */
    public function testBBCodeListEgalUnCreeUneListeOrdonnee()
    {
        $texteAvecBbcode = "[list=]Ceci n'est pas une liste[/list]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<ol>Ceci n'est pas une liste</ol>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [list=] crée une liste ordonnée
     */
    public function testBBCodeListEgalCreeUneListeOrdonnee()
    {
        $texteAvecBbcode = "[list=]Ceci n'est pas une liste[/list]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<ol>Ceci n'est pas une liste</ol>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [list] crée une liste non ordonnée
     */
    public function testBBCodeListCreeUneListeNonOrdonnee()
    {
        $texteAvecBbcode = "[list]Ceci n'est pas une liste[/list]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<ul>Ceci n'est pas une liste</ul>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [*] doit être refermé pour être un élément de liste
     */
    public function testBBCodeEtoileDoitEtreReferme()
    {
        $texteAvecBbcode = "[*]Ceci est un élément de liste[/*]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<li>Ceci est un élément de liste</li>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [exposant] utilise la balise html sup
     */
    public function testBBCodeExposantUtiliseSup()
    {
        $texteAvecBbcode = "[exposant]Ceci est un exposant[/exposant]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<sup>Ceci est un exposant</sup>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [indice] utilise la balise html sub
     */
    public function testBBCodeIndiceUtiliseSub()
    {
        $texteAvecBbcode = "[indice]Ceci est un indice[/indice]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<sub>Ceci est un indice</sub>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [size=xx-small] utilise la balise html span
     */
    public function testBBCodeSizeAvecLettres()
    {
        $texteAvecBbcode = "[size=xx-small]Ceci est très petit[/size]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"font-size: xx-small;\">Ceci est très petit</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [size=12] utilise la balise html span
     */
    public function testBBCodeSizeAvecChiffres()
    {
        $texteAvecBbcode = "[size=12]Ceci est de taille normale[/size]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"font-size: 12px;\">Ceci est de taille normale</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [color=#a1e2B3] utilise la balise html span
     */
    public function testBBCodeColorHexadecimal()
    {
        $texteAvecBbcode = "[color=#a1e2B3]Ceci est d'une autre couleur[/color]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"color: #a1e2B3;\">Ceci est d'une autre couleur</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [quote] utilise la balise html fieldset
     */
    public function testBBCodeQuoteUtiliseUnFieldset()
    {
        $texteAvecBbcode = "[quote]Une citation sans auteur[/quote]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<fieldset class=\"quote\"><legend>Citation</legend></legend>Une citation sans auteur</fieldset>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [quote] fonctionne quand on précise un auteur
     */
    public function testBBCodeQuoteAvecAuteurFonctionne()
    {
        $texteAvecBbcode = "[quote=\"Shûji\"]un alex roi c'est le O+ du groupe sanguin, il aime tout, les awards il a pas de préférence[/quote]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<fieldset class=\"quote\"><legend><strong>Shûji</strong> a écrit :</legend>un alex roi c'est le O+ du groupe sanguin, il aime tout, les awards il a pas de préférence</fieldset>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [color=darkred] utilise la balise html span
     */
    public function testBBCodeColorWord()
    {
        $texteAvecBbcode = "[color=darkred]Ceci est d'une autre couleur[/color]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"color: darkred;\">Ceci est d'une autre couleur</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [left] utilise la balise html span
     */
    public function testBBCodeLeft()
    {
        $texteAvecBbcode = "[left]Ceci est à gauche[/left]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"text-align: left;\">Ceci est à gauche</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [center] utilise la balise html span
     */
    public function testBBCodeCenter()
    {
        $texteAvecBbcode = "[center]Ceci est centré[/center]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"text-align: center;\">Ceci est centré</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [right] utilise la balise html span
     */
    public function testBBCodeRight()
    {
        $texteAvecBbcode = "[right]Ceci est à droite[/right]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"text-align: right;\">Ceci est à droite</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [justify] utilise la balise html span
     */
    public function testBBCodeJustify()
    {
        $texteAvecBbcode = "[justify]Ceci est justifié[/justify]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<span style=\"text-align: justify;\">Ceci est justifié</span>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [hr] utilise la balise html hr
     */
    public function testBBCodeHr()
    {
        $texteAvecBbcode = "Partie 1[hr]Partie 2";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "Partie 1<hr>Partie 2";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [code] utilise la balise html code
     */
    public function testBBCodeCode()
    {
        $texteAvecBbcode = "[code]<script lang=\"javascript\">var a = 'session ' + 2011; console.log(a);</script>[/code]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<fieldset class=\"quote\"><legend><strong>Code</strong></legend><code><script lang=\"javascript\">var a = 'session ' + 2011; console.log(a);</script></code></fieldset>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [spoiler] utilise la balise html fieldset
     */
    public function testBBCodeSpoiler()
    {
        $texteAvecBbcode = "[spoiler]Ceci est secret[/spoiler]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<fieldset class=\"spoiler\"><legend class=\"titre-spoiler\" onclick=\"inverserSpoiler(this.nextElementSibling);\">Secret (Cliquer pour afficher)</legend><div class=\"contenu-spoiler\">Ceci est secret</div></fieldset>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [spoil] utilise la balise html fieldset
     */
    public function testBBCodeSpoil()
    {
        $texteAvecBbcode = "[spoil]Ceci est secret[/spoil]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<fieldset class=\"spoiler\"><legend class=\"titre-spoiler\" onclick=\"inverserSpoiler(this.nextElementSibling);\">Secret (Cliquer pour afficher)</legend><div class=\"contenu-spoiler\">Ceci est secret</div></fieldset>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [hide] utilise la balise html fieldset
     */
    public function testBBCodeHide()
    {
        $texteAvecBbcode = "[hide=Liste des beta-testeurs]*Grosse liste à remplir*[/hide]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<fieldset class=\"spoiler\"><legend class=\"titre-spoiler\" onclick=\"inverserSpoiler(this.nextElementSibling);\">Liste des beta-testeurs</legend><div class=\"contenu-spoiler\">*Grosse liste à remplir*</div></fieldset>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [twitch] utilise la balise html iframe
     */
    public function testBBCodeTwitch()
    {
        $texteAvecBbcode = "[twitch]1234567[/twitch]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<iframe src=\"https://player.twitch.tv/?autoplay=false&video=v1234567\" allowfullscreen=\"true\" height=\"378\" width=\"620\" style=\"border: 0; overflow: hidden;\"></iframe>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Le BBCode [twitch=clip] utilise la balise html iframe
     */
    public function testBBCodeClipTwitch()
    {
        $texteAvecBbcode = "[twitch=clip]IncredibleSpeedrunnerHavingItems[/twitch]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<iframe src=\"https://clips.twitch.tv/embed?autoplay=false&clip=IncredibleSpeedrunnerHavingItems\" allowfullscreen=\"true\" height=\"378\" width=\"620\" style=\"border: 0; overflow: hidden;\"></iframe>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox Un lien sans BBCode utilise la balise a
     */
    public function testLienSansBBCodeUtiliseLaBaliseA()
    {
        $texteAvecBbcode = "Allez inscrire votre jeu ici : https://alexdor.info/?p=inscjeu ";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "Allez inscrire votre jeu ici : <a href=\"https://alexdor.info/?p=inscjeu\">https://alexdor.info/?p=inscjeu</a> ";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    // Tests d'intégration

    /**
     * @testdox On peut mettre un BBCode gras dans un italique
     */
    public function testBBCodeGrasDansItalique()
    {
        $texteAvecBbcode = "[i][g]C'est italique et gras[/g][/i]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<em><strong>C'est italique et gras</strong></em>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }

    /**
     * @testdox On peut mettre un BBCode gras dans un spoiler
     */
    public function testBBCodeGrasDansSpoiler()
    {
        $texteAvecBbcode = "[spoiler][g]C'est italique et gras[/g][/spoiler]";

        $htmlReel = $this->bbCode->convertToHtml($texteAvecBbcode);

        $htmlAttendu = "<fieldset class=\"spoiler\"><legend class=\"titre-spoiler\" onclick=\"inverserSpoiler(this.nextElementSibling);\">Secret (Cliquer pour afficher)</legend><div class=\"contenu-spoiler\"><strong>C'est italique et gras</strong></div></fieldset>";
        $this->assertEquals($htmlAttendu, $htmlReel);
    }
}
