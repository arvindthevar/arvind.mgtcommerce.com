<?php
namespace Cloudsys\AdvanceContact\Ui\Component\Listing\Column;

use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Grid action class
 *
 */
class Actions extends Column
{

    /**
     * Escaper.
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * Url Builder
     *
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * Constructor.
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Escaper            $escaper
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Escaper $escaper,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->escaper = $escaper;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $this->prepareItem($item);
            }
        }

        return $dataSource;
    }

    /**
     * Get data.
     *
     * @param array $item
     *
     * @return string
     */
    protected function prepareItem(array &$item)
    {
        $itemsAction = $this->getData('itemsAction');
        $indexField = $this->getData('config/indexField');
        if (isset($item[$indexField])) {
            foreach ($itemsAction as $key => $itemAction) {
                $path = isset($itemAction['path']) ? $itemAction['path'] : null;
                $urlParams = [$indexField => $item[$indexField]];
                if(isset($itemAction["param"]))
                {
                    foreach($itemAction["param"] as $pkey => $param)
                    {
                        $urlParams[$pkey] = $param;
                    }
                }
                $itemAction['href'] = $this->urlBuilder->getUrl(
                    $path,
                    $urlParams
                );
                $item[$this->getData('name')][$key] = $itemAction;
            }
        }
    }
}
