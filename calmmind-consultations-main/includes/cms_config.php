<?php
/**
 * CalmMind Consultation - CMS Configuration
 */

return [
    'home' => [
        'label' => 'Home Page',
        'sections' => [
            'hero_title' => ['label' => 'Hero Title', 'type' => 'text', 'has_image' => false],
            'hero_text' => ['label' => 'Hero Lead Text', 'type' => 'textarea', 'has_image' => true],
            'why_choose_intro' => ['label' => 'Why Choose Intro', 'type' => 'textarea', 'has_image' => false],
            'feature_matching_title' => ['label' => 'Feature Matching Title', 'type' => 'text', 'has_image' => false],
            'feature_matching_text' => ['label' => 'Feature Matching Text', 'type' => 'textarea', 'has_image' => false],
            'feature_modes_title' => ['label' => 'Feature Modes Title', 'type' => 'text', 'has_image' => false],
            'feature_modes_text' => ['label' => 'Feature Modes Text', 'type' => 'textarea', 'has_image' => false],
            'feature_evidence_title' => ['label' => 'Feature Evidence Title', 'type' => 'text', 'has_image' => false],
            'feature_evidence_text' => ['label' => 'Feature Evidence Text', 'type' => 'textarea', 'has_image' => false],
        ]
    ],
    'about' => [
        'label' => 'About Page',
        'sections' => [
            'intro_title' => ['label' => 'Intro Title', 'type' => 'text', 'has_image' => false],
            'intro_text_1' => ['label' => 'Intro Paragraph 1', 'type' => 'textarea', 'has_image' => false],
            'intro_text_2' => ['label' => 'Intro Paragraph 2', 'type' => 'textarea', 'has_image' => false],
            'snapshot_title' => ['label' => 'Snapshot Title', 'type' => 'text', 'has_image' => false],
            'snapshot_image' => ['label' => 'Snapshot Image', 'type' => 'text', 'has_image' => true],
            'snapshot_point_1' => ['label' => 'Snapshot Point 1', 'type' => 'text', 'has_image' => false],
            'snapshot_point_2' => ['label' => 'Snapshot Point 2', 'type' => 'text', 'has_image' => false],
            'snapshot_point_3' => ['label' => 'Snapshot Point 3', 'type' => 'text', 'has_image' => false],
            'snapshot_point_4' => ['label' => 'Snapshot Point 4', 'type' => 'text', 'has_image' => false],
        ]
    ],
    'services' => [
        'label' => 'Services Page',
        'sections' => [
            'intro_title' => ['label' => 'Intro Title', 'type' => 'text', 'has_image' => false],
            'intro_text' => ['label' => 'Intro Text', 'type' => 'textarea', 'has_image' => false],
            'therapy_title' => ['label' => 'Therapy Title', 'type' => 'text', 'has_image' => false],
            'therapy_text' => ['label' => 'Therapy Text', 'type' => 'textarea', 'has_image' => false],
            'relationships_title' => ['label' => 'Relationships Title', 'type' => 'text', 'has_image' => false],
            'relationships_text' => ['label' => 'Relationships Text', 'type' => 'textarea', 'has_image' => false],
            'coaching_title' => ['label' => 'Coaching Title', 'type' => 'text', 'has_image' => false],
            'coaching_text' => ['label' => 'Coaching Text', 'type' => 'textarea', 'has_image' => false],
            'sidebar_title' => ['label' => 'Sidebar Title', 'type' => 'text', 'has_image' => false],
            'sidebar_image' => ['label' => 'Sidebar Image', 'type' => 'text', 'has_image' => true],
        ]
    ],
    'contact' => [
        'label' => 'Contact Page',
        'sections' => [
            'intro_title' => ['label' => 'Intro Title', 'type' => 'text', 'has_image' => false],
            'intro_text' => ['label' => 'Intro Text', 'type' => 'textarea', 'has_image' => false],
            'sidebar_image' => ['label' => 'Sidebar Image', 'type' => 'text', 'has_image' => true],
        ]
    ],
    'privacy' => [
        'label' => 'Privacy Policy',
        'sections' => [
            'title' => ['label' => 'Policy Title', 'type' => 'text', 'has_image' => false],
            'content' => ['label' => 'Policy Content', 'type' => 'textarea', 'has_image' => false],
            'banner_image' => ['label' => 'Banner Image', 'type' => 'text', 'has_image' => true],
        ]
    ]
];
