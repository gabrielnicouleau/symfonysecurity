<?php

namespace App\Service;

class UtilsService
{

    public function sanitize(string $chaine)
    {
        $chaine = trim($chaine);
        $chaine = strip_tags($chaine);
        $chaine = htmlspecialchars($chaine, ENT_NOQUOTES);
        return $chaine;
    }

    public function sanitizeArray(array $data): array
    {
        $cleanData = [];
        foreach ($data as $key => $value) {
            if (gettype($value) == "array") {
                foreach ($value as $key1 => $value1) {
                    $cleanData[$key1] = $this->sanitize($value1);
                }
            } else if ($key != '_token') {
                $cleanData[$key] = $this->sanitize($value);
            } else {
                $cleanData[$key] = $value;
            }
        }
        return $cleanData;
    }
}