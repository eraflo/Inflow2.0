<?php

namespace App\Service;

class MentionsAndTagsReplacer
{
    public function replaceMentions(string $text, array $mentionedUsers) {
        if (empty($mentionedUsers)) {
            return $text;
        }
        $i = 0;
        $text = preg_replace_callback(
            "/@\w+/",
            function ($matches) use ($mentionedUsers, $i) {
                if ("@".$mentionedUsers[$i]?->getUsername() === $matches[0]) {
                    $id = $mentionedUsers[$i]->getId();
                    $i++;
                    return "<a href='/users/" . $id . "'>" . $matches[0] . "</a>";
                } else {
                    return $matches[0];
                }
            },
            $text
        );
        return $text;
    }

    public function replaceTags(string $text) {
        $text = preg_replace(
            "/#(\w+)/",
            "<a href='/search?tag=$1'>#$1</a>",
            $text
        );
        //dd($text);
        return $text;
    }
}