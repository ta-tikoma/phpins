<?php

namespace Phpins\Validators\INotForgottenAddUseThisClass;

use Phpins\Entities\Error;

trait FindDefinedClasses
{
    /**
     * Все указанные классы
     */
    private function getDefinedClasses(string $filePath, string $fileContent) : array
    {
        $classIncludes = [];
        $usesMatches = null;

        // те что указаны через use
        preg_match_all(
            '/^use ([a-zA-Z0-9]+\\\\)*([a-zA-Z0-9]+ as )*([a-zA-Z0-9]+);/m',
            $fileContent,
            $usesMatches,
            PREG_OFFSET_CAPTURE,
            0
        );
        if (!is_null($usesMatches)) {
            foreach ($usesMatches[3] as $usesMatch) {
                $classIncludes[$usesMatch[0]] = $usesMatch[1];
            }
        }

        // те что лежат в той же папке
        foreach (scandir(dirname($filePath)) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $parts = explode('.', $file);
            if (count($parts) != 2) {
                continue;
            }
            list($name,) = $parts;

            if (isset($classIncludes[$name])) {
                $this->errors[] = new Error(
                    $classIncludes[$name],
                    Error::TYPE_WARNING,
                    'Class in current namespace "'.$name.'"'
                );
            } else {
                $classIncludes[$name] = null;
            }
        }

        return $classIncludes;
    }
}
