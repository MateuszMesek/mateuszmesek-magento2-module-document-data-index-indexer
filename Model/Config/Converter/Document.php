<?php declare(strict_types=1);

namespace MateuszMesek\DocumentDataIndexIndexer\Model\Config\Converter;

use DOMNode;
use MateuszMesek\Framework\Config\Converter\AttributeValueResolver;
use MateuszMesek\Framework\Config\Converter\ChildrenResolver;
use MateuszMesek\Framework\Config\Converter\ProcessorInterface;

class Document implements ProcessorInterface
{
    private const NODES = [
        'action',
        'dimensionProvider',
        'entityIdsResolver',
        'indexNamesProvider',
        'indexNameResolver',
        'indexNamePattern',
        'indexStructureBuilder',
        'indexIdsResolver',
        'dataResolver',
        'readHandler',
        'saveHandler'
    ];

    public function __construct(
        private readonly AttributeValueResolver $attributeValueResolver,
        private readonly ChildrenResolver       $childrenResolver
    )
    {
    }

    public function process(DOMNode $node): array
    {
        $data = [
            'name' => $this->attributeValueResolver->resolve($node, 'name')
        ];

        foreach ($this->childrenResolver->resolve($node) as $child) {
            if (!in_array($child->nodeName, self::NODES, true)) {
                continue;
            }

            $data[$child->nodeName] = $this->attributeValueResolver->resolve($child, 'name');
        }

        return $data;
    }
}
