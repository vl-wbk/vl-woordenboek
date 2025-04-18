<?php

namespace App\Contracts\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface HandlesRelationManipulationInterface
 *
 * This interface defines a contract for classes that handle the manipulation of Eloquent model relations.
 * It provides methods for syncing, attaching, detaching, associating, and dissociating related models.
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @source  https://github.com/czim/laravel-repository/blob/master/src/Contracts/HandlesEloquentRelationManipulationInterface.php
 * @package App\Contracts\Concerns
 *  */
interface HandlesRelationManipulationInterface
{
    /**
     * Syncs the given relation on the model with the provided IDs.
     *
     * This method connects the model to the models with the given IDs for the specified relation.
     * It can also detach existing relations that are not in the provided IDs list.
     *
     * @param TModel                  $model      The Eloquent model to manipulate.
     * @param string                  $relation   The name of the relation (method name on the model).
     * @param array<int, int|string>  $ids        List of IDs to connect to the relation.
     * @param bool                    $detaching  Whether to detach existing relations not in the IDs list (default: true).
     */
    public function sync(Model $model, string $relation, array $ids, bool $detaching = true): void;

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
    public function attach(Model $model, string $relation, int|string $id, array $attributes = [], bool $touch = true): void;

    /**
     * Detaches related models from the given relation on the model.
     *
     * This method disconnects the model from the related models with the given IDs.
     * If no IDs are provided, it detaches all related models from the relation.
     *
     * @param TModel                  $model     The Eloquent model to manipulate.
     * @param string                  $relation  The name of the relation (method name on the model).
     * @param array<int, int|string>  $ids       List of IDs to detach from the relation (optional).
     * @param bool                    $touch     Whether to touch the parent model's timestamp (default: true).
     */
    public function detach(Model $model, string $relation, array $ids = [], bool $touch = true): void;

    /**
     * Associates a single related model to the given relation on the model.
     *
     * This method sets the foreign key on the model to the ID of the related model.
     * It is typically used for one-to-one or one-to-many relations.
     *
     * @param TModel             $model     The Eloquent model to manipulate.
     * @param string             $relation  The name of the relation (method name on the model).
     * @param TModel|int|string  $with      The related model instance, ID, or key to associate.
     */
    public function associate(Model $model, string $relation, Model|int|string $with): void;

    /**
     * Dissociates the related model from the given relation on the model.
     *
     * This method sets the foreign key on the model to null, effectively disconnecting the related model.
     * It is typically used for one-to-one or one-to-many relations.
     *
     * @param TModel  $model     The Eloquent model to manipulate.
     * @param string  $relation  The name of the relation (method name on the model).
     */
    public function dissociate(Model $model, string $relation): void;
}
