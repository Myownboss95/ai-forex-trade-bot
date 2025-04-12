<?php

namespace App\Enums;

enum CmsType: string
{
    case Hero1 = 'hero1';
    case Hero2 = 'hero2';
    case Section1 = 'section1';
    case Section2 = 'section2';
    case Section3 = 'section3';
    case SectionCharity = 'section_charity';

    public function title(): string
    {
        return match ($this) {
            self::Hero1 => 'Hero 1',
            self::Hero2 => 'Hero 2',
            self::Section1 => 'Section 1',
            self::Section2 => 'Section 2',
            self::Section3 => 'Section 3',
            self::SectionCharity => 'Charity Section',
        };
    }
}
