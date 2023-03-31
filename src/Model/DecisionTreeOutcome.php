<?php

namespace DNADesign\SilverStripeElementalDecisionTree\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use Chrometoaster\Forms\FieldsProvider;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Control\Controller;
use DNADesign\SilverStripeElementalDecisionTree\Forms\HasOneSelectOrCreateField;

class DecisionTreeOutcome extends DataObject
{
    /**
     * Human-readable singular name.
     *
     * @var string
     */
    private static $singular_name = 'Outcome';

    /**
     * Human-readable plural name.
     *
     * @var string
     */
    private static $plural_name = 'Outcomes';

    /**
     * Table associated with the model.
     *
     * @var string
     */
    private static $table_name = 'DecisionTreeOutcome';

    /**
     * DB fields.
     *
     * @var array
     */
    private static $db = [
        'Title'             => 'Text',
        'Content'           => 'HTMLText',
    ];

    /**
     * Summary fields.
     *
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Outcome',
    ];


    public function getCMSFields()
    {
        $fields = FieldList::create();

        $fieldTitle = TextField::create('Title', 'Title', '', 50);
        $fieldTitle->setDescription('Maximum 50 characters');

        $fieldContent = FieldsProvider::getSimplifiedHTMLEditorField('Content', 'Content to display for this outcome');

        $fields->merge([
            $fieldTitle,
            $fieldContent,
        ]);

        return $fields;
    }
}
