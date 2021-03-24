<?php

declare(strict_types=1);

namespace App\Domain\Fixer\Phpdoc;

use PhpCsFixer\DocBlock\Annotation;
use PhpCsFixer\DocBlock\DocBlock;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

final class PhpdocThrowsSortFixer implements FixerInterface
{
    private const THROWS_TAG = 'throws';

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_DOC_COMMENT);
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        if (0 === $tokens->count() || !$this->isCandidate($tokens) || !$this->supports($file)) {
            return;
        }

        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind(T_DOC_COMMENT)) {
                continue;
            }

            $content = $token->getContent();
            $content = $this->sortThrowsAnnotations($content);
            $tokens[$index] = new Token([T_DOC_COMMENT, $content]);
        }
    }

    public function getName(): string
    {
        return 'App/phpdoc_throws_sort';
    }

    /**
     * Must run after PhpdocOrderFixer
     */
    public function getPriority(): int
    {
        return -3;
    }

    public function supports(SplFileInfo $file): bool
    {
        return true;
    }

    private function sortThrowsAnnotations(string $content): string
    {
        $doc = new DocBlock($content);
        $throwsAnnotations = $doc->getAnnotationsOfType(static::THROWS_TAG);

        if (0 === count($throwsAnnotations)) {
            return $content;
        }

        $orderedThrowsAnnotationsContent = array_map(static function (Annotation $annotation): string {
            return $annotation->getContent();
        }, $throwsAnnotations);

        usort($orderedThrowsAnnotationsContent, static function (string $a, string $b): int {
            return $a <=> $b;
        });

        $start = reset($throwsAnnotations)->getStart();
        $end = end($throwsAnnotations)->getEnd();
        $idx = 0;

        foreach ($doc->getAnnotations() as $annotation) {
            if ($annotation->getStart() < $start || $annotation->getStart() > $end) {
                continue;
            }

            $doc
                ->getLine($annotation->getStart())
                ->setContent($orderedThrowsAnnotationsContent[$idx])
            ;

            ++$idx;
        }

        return $doc->getContent();
    }
}
