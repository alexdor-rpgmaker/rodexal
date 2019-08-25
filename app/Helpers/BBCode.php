<?php

namespace App\Helpers;

use Genert\BBCode\BBCode as GenertBBCode;

// More info: https://stackoverflow.com/questions/28290332/best-practices-for-custom-helpers-in-laravel-5

class BBCode
{
  public static function construireParserBBCode()
  {
    $bbCode = new GenertBBCode();

    $bbCode->addParser(
      'bold',
      '/\[(g|b)\](.*?)\[\/(g|b)\]/s',
      '<strong>$2</strong>',
      '$2'
    );
    $bbCode->addParser(
      'italic',
      '/\[i\](.*?)\[\/i\]/s',
      '<em>$1</em>',
      '$1'
    );
    $bbCode->addParser(
      'strikethrough',
      '/\[(s|strike)\](.*?)\[\/(s|strike)\]/s',
      '<s>$2</s>',
      '$2'
    );

    $bbCode->addParser(
      'img-2x',
      '/\[img-2x\](.*?)\[\/img-2x\]/s',
      '<img style="width: 50%;" src="$1">',
      '$1'
    );

    $bbCode->addParser(
      'orderedlistnumerical',
      '/\[list=1?\](.*?)\[\/list\]/s',
      '<ol>$1</ol>',
      '$1'
    );
    $bbCode->addParser(
      'listitem',
      '/\[\*\](.+)\[\/\*\]/',
      '<li>$1</li>',
      '$1'
    );

    $bbCode->addParser(
      'exposant',
      '/\[exposant\](.*?)\[\/exposant\]/s',
      '<sup>$1</sup>',
      '$1'
    );
    $bbCode->addParser(
      'indice',
      '/\[indice\](.*?)\[\/indice\]/s',
      '<sub>$1</sub>',
      '$1'
    );

    $bbCode->addParser(
      'size-with-letters',
      '/\[size=(xx-small|x-small|small|medium|large|x-large|xx-large)\](.*?)\[\/size\]/s',
      '<span style="font-size: $1;">$2</span>',
      '$2'
    );
    $bbCode->addParser(
      'size',
      '/\[size=([0-9]{2,3})\](.*?)\[\/size\]/s',
      '<span style="font-size: $1px;">$2</span>',
      '$2'
    );

    $bbCode->addParser(
      'color-with-hexa',
      '/\[color=\#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})\](.*?)\[\/color\]/s',
      '<span style="color: #$1;">$2</span>',
      '$2'
    );
    $bbCode->addParser(
      'color',
      '/\[color=(darkred|red|green|pink|darkblue|blue|yellow|cyan|orange|brown|indigo|purple|violet|olive|white|black)\](.*?)\[\/color\]/s',
      '<span style="color: $1;">$2</span>',
      '$2'
    );

    $bbCode->addParser(
      'left',
      '/\[left\](.*?)\[\/left\]/s',
      '<span style="text-align: left;">$1</span>',
      '$1'
    );
    $bbCode->addParser(
      'center',
      '/\[center\](.*?)\[\/center\]/s',
      '<span style="text-align: center;">$1</span>',
      '$1'
    );
    $bbCode->addParser(
      'right',
      '/\[right\](.*?)\[\/right\]/s',
      '<span style="text-align: right;">$1</span>',
      '$1'
    );
    $bbCode->addParser(
      'justify',
      '/\[justify\](.*?)\[\/justify\]/s',
      '<span style="text-align: justify;">$1</span>',
      '$1'
    );

    $bbCode->addParser(
      'hr',
      '/\[hr\]/s',
      '<hr>',
      ''
    );

    $bbCode->addParser(
      'quote',
      '/\[quote\](.*?)\[\/quote\]/s',
      '<fieldset class="quote"><legend>Citation</legend></legend>$1</fieldset>',
      '$1'
    );
    $bbCode->addParser(
      'quote-with-author',
      '/\[quote=(&quot;|")(.+)(&quot;|")\](.*?)\[\/quote\]/s',
      '<fieldset class="quote"><legend><strong>$2</strong> a écrit :</legend>$4</fieldset>',
      '$4'
    );
    $bbCode->addParser(
      'code',
      '/\[code\](.*?)\[\/code\]/s',
      '<fieldset class="quote"><legend><strong>Code</strong></legend><code>$1</code></fieldset>',
      '$1'
    );
    $bbCode->addParser(
      'spoiler',
      '/\[(spoil|spoiler)\](.+)\[\/(spoil|spoiler)\]/s',
      '<fieldset class="spoiler"><legend class="titre-spoiler" onclick="inverserSpoiler(this.nextElementSibling);">Secret (Cliquer pour afficher)</legend><div class="contenu-spoiler">$2</div></fieldset>',
      '$2'
    );
    $bbCode->addParser(
      'hide',
      '/\[hide=(.+)\](.+)\[\/hide\]/s',
      '<fieldset class="spoiler"><legend class="titre-spoiler" onclick="inverserSpoiler(this.nextElementSibling);">$1</legend><div class="contenu-spoiler">$2</div></fieldset>',
      '$2'
    );

    // Réseaux

    $bbCode->addParser(
      'twitch',
      '/\[twitch\](.+)\[\/twitch\]/s',
      '<iframe src="https://player.twitch.tv/?autoplay=false&video=v$1" frameborder="0" allowfullscreen="true" scrolling="no" height="378" width="620"></iframe>',
      '$1'
    );

    // Plus vraiment un BBCode

    $bbCode->addParser(
      'link-without-url',
      '/(\s|\()+((https:\/\/|http:\/\/)[a-zA-Z0-9\.\+\#_\/%-]+(\?[a-zA-Z0-9_\/\#-]+(=[a-zA-Z0-9\.\#_\/%-]+((&|&amp;)[a-zA-Z0-9_\/\#-]+(=[a-zA-Z0-9\.\#_\/%-]+)*)*)*)?){1}(\s|\))+/s',
      '$1<a href="$2">$2</a>$9',
      '$1$2$9'
    );

    // TODO : smileys
    // TODO : son
    // TODO : cadre, soundcloud
    // TODO : include:awards2013, include:awards2014, include:userbars2014

    return $bbCode;
  }
}
