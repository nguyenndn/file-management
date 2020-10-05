<?php

namespace GGPHP\FileMedia\Transformers;

use Illuminate\Support\Arr;
use League\Fractal\TransformerAbstract;

/**
 * Class BaseTransformer.
 *
 * @package namespace App\Transformers;
 */
class BaseTransformer extends TransformerAbstract
{
    /**
     * Array attribute doesn't parse.
     */
    public $ignoreAttributes = [];

    /**
     * Transform the all fillable entity.
     *
     * @param $model
     *
     * @return array
     */
    public function fromFillable($model)
    {
        $hiddens = array_merge($model->getHidden(), $this->ignoreAttributes);

        $fillables = $model->getFillable();

        if (method_exists($model, 'getDateTimeFields')) {
            $fillables = array_merge($fillables, $model->getDateTimeFields());
        }
        $fillableValues = Arr::only($model->toArray(), array_diff($fillables, $hiddens));

        return array_merge($fillableValues, $this->customAttributes($model), [
            'id' => (int) $model->id,
        ]);
    }

    /**
     * Transform the custom field entity.
     *
     * @return array
     */
    public function customAttributes($model): array
    {
        return [];
    }

    /**
     * Transform the custom field entity.
     *
     * @return array
     */
    public function customMeta(): array
    {
        return [];
    }

    /**
     * Transform the entity.
     *
     * @param $model
     *
     * @return array
     */
    public function transform($model)
    {
        $meta = $this->getCurrentScope()->getResource()->getMeta();

        $meta = array_merge($meta, $this->customMeta());

        $this->getCurrentScope()->getResource()->setMeta($meta);

        return $this->fromFillable($model);
    }
}
