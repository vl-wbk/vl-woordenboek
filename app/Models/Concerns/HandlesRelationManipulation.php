<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Contracts\Eloquent\HandlesRelationManipulationInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HandlesEloquentRelationManipulationTrait
 *
 * This trait provides a reusable implementation for handling Eloquent model relations.
 * It includes methods to sync, attach, detach, associate, and dissociate related models, making it easier to manage relationships between entities in the application.
 *
 * This trait is designed to be used in Eloquent models that require relation manipulation.
 * It expects the model to have defined the necessary relations (e.g., hasMany, belongsTo, belongsToMany).
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @see HandlesRelationManipulationInterface This trait implements the methods defined in this interface.
 * @source https://github.com/czim/laravel-repository/blob/master/src/Traits/HandlesEloquentRelationManipulationTrait.php Inspiration for this trait.
 * @package App\Models\Concerns
 */
trait HandlesRelationManipulation
{
    /**
     * Syncs the given relation on the model with the provided IDs.
     *
     * This method connects the model to the models with the given IDs for the specified relation.
     * It can also detach existing relations that are not present in the provided IDs list.
     *
     * @param TModel                  $model       The Eloquent model to manipulate.
     * @param string                  $relation    The name of the relation (method name on the model).
     * @param array<int, int|string>  $ids         List of IDs to connect to the relation.
     * @param bool                    $detaching   Whether to detach existing relations not in the IDs list (default: true).
     */
    public function sync(Model $model, string $relation, array $ids, bool $detaching = true): void
    {
        $model->{$relation}()->sync($ids, $detaching);
    }

    /**
     * Attaches a single related model to the given relation on the model.
     *
     * This method connects the model to a single related model with the given ID.
     * It also allows passing additional attributes to be stored in the pivot table for many-to-many relations.
     *
     * @param TModel                $model       The Eloquent model to manipulate.
     * @param string                $relation    The name of the relation (method name on the model).
     * @param int|string            $id          The ID of the related model to attach.
     * @param array<string, mixed>  $attributes  Additional attributes to store in the pivot table (for many-to-many relations).
     * @param bool                  $touch       Whether to touch the parent model's timestamp (default: true).
     */
    public function attach(Model $model, string $relation, int|string $id, array $attributes = [], bool $touch = true): void
    {
        $model->{$relation}()->attach($id, $attributes, $touch);
    }

    /**
     * Detaches related models from the given relation on the model.
     *
     * This method disconnects the model from the related models with the given IDs.
     * If no IDs are provided, it detaches all related models from the relation.
     *
     * @param TModel                  $model     The Eloquent model to manipulate.
     * @param string                  $relation  The name of the relation (method name on the model).
     * @param array<int, int|string>  $ids       List of IDs to detach from the relation.
     * @param bool                    $touch     Whether to touch the parent model's timestamp (default: true).
     */
    public function detach(Model $model, string $relation, array $ids = [], bool $touch = true): void
    {
        $model->{$relation}()->detach($ids, $touch);
    }

    /**
     * Associates a single related model to the given relation on the model.
     *
     * This method sets the foreign key on the model to the ID of the related model.
     * It is typically used for one-to-one or one-to-many relations.
     *
     * @param TModel            $model     The Eloquent model to manipulate.
     * @param string            $relation  The name of the relation (method name on the model).
     * @param Model|int|string  $with      The related model instance, ID, or key to associate.
     */
    public function associate(Model $model, string $relation, Model|int|string $with): void
    {
        $model->{$relation}()->associate($with);
    }

    /**
     * Dissociates the related model from the given relation on the model.
     *
     * This method sets the foreign key on the model to null, effectively disconnecting the related model.
     * It is typically used for one-to-one or one-to-many relations.
     *
     * @param  TModel $model     The Eloquent model to manipulate.
     * @param  string $relation  The name of the relation (method name on the model).
     */
    public function dissociate(Model $model, string $relation): void
    {
        $model->{$relation}()->dissociate();
    }
}
