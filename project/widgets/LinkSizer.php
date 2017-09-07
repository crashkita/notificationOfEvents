<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Html;

/**
 * Class LinkSizer
 * @package app\widgets
 */
class LinkSizer extends Widget
{
    /**
     * @var Pagination the pagination object that this pager is associated with.
     * You must set this property in order to make LinkPager work.
     */
    public $pagination;

    /**
     * @var array items per page sizes variants
     */
    public $sizerVariants = [5, 10, 25, 50, 100];

    public $options = ['class' => 'pagination'];

    public $linkOptions = ['class' => 'page-link'];

    public $itemOptions = ['class' => 'page-item'];

    public $activePageCssClass = 'active';

    /**
     * @var string the text shown before sizer links. Defaults to empty.
     */
    public $header = 'Отображать по: ';

    public $wrapperOptions = ['class' => 'pager'];


    /**
     * Initializes the pager.
     */
    public function init()
    {
        if ($this->pagination === null) {
            throw new InvalidConfigException('The "pagination" property must be set.');
        }
    }

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run()
    {
        parent::run();
        $links = $this->renderSizeLinks();

        if (empty($links)) {
            return '';
        }

        return Html::tag('div', $this->getHeader() . $links, $this->wrapperOptions);
    }

    protected function getHeader()
    {
        if (empty($this->header)) {
            return '';
        }

        return Html::tag('div', $this->header);
    }

    protected function getPageSizes()
    {
        $totalCount = $this->pagination->totalCount;
        $pageSizeLimit = array_filter(
            $this->sizerVariants,
            function ($item) use ($totalCount) {
                return $item < $totalCount ? $item : null;
            }
        );
        return $pageSizeLimit;
    }

    /**
     * @return string
     */
    protected function renderSizeLinks()
    {
        $pageSizes = $this->getPageSizes();

        if (count($pageSizes) <= 1) {
            return '';
        }

        $currentPageSize = $this->pagination->getPageSize();

        $links = [];
        foreach ($pageSizes as $pageSize) {
            $links[] = $this->renderSizeLink($pageSize, $currentPageSize == $pageSize);
        }

        return Html::tag('ul', implode("\n", $links), $this->options);
    }

    /**
     * @param string $pageSize text for link
     * @param bool $active is current page size
     * @return string
     */
    protected function renderSizeLink($pageSize, $active)
    {
        $options = $this->itemOptions;

        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }

        return Html::tag('li', Html::a($pageSize, $this->pagination->createUrl(0, $pageSize), $this->linkOptions), $options);
    }
}