/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { ObjectUtils } from '../utils/ObjectUtils';


/**
 * Model history management class
 *
 * @see constructor()
 */
export class ModelHistoryManager<T> {


    /**
     * An instance that will be created with the provided model class type and contains
     * the current model state.
     */
    private _instance: T;


    /**
     * A list with all the model instances that are saved as snapshots
     */
    private _instancesHistory: T[] = [];

    /**
     * Manages the change history for a given model class.
     *
     * When ModelHistoryManager is created, we provide the class type we want to instantiate. ModelHistoryManager
     * takes the responsability of creating the class instance, and internally track any of the changes.
     *
     * We will then be able to save snapshots and track the changes on the model class instance,
     * so we can perform undo and redo operations at any time to restore the class state to any of
     * the previously saved snapshots.
     *
     * @param typeConstructor The class type that will be used by the ModelHistoryManager. A new fresh instance
     * is inmediately created by this constructor.
     */
    constructor(private typeConstructor: new () => T) {

        this._instance = new this.typeConstructor();
    }


    /**
     * The current model class instance
     */
    get get(): T {

        return this._instance;
    }


    /**
     * Array containing all the snapshots that have been saved till the current
     * moment. Each one of the array elements is a model class instance containing all
     * the information that was available at the moment of taking the snapshot
     * 
     * WARNING !! - This value must be used only to read data. Avoid direct modification of
     * the returned array to prevent unwanted behaviours 
     */
    get snapshots() {

        return this._instancesHistory;
    }


    /**
     * Save a copy of the current model class instance state so it can be later retrieved.
     */
    saveSnapShot() {

        this._instancesHistory.push(ObjectUtils.clone(this._instance));
    }


    /**
     * True if any snapshot of the model class instance exist, false otherwise
     */
    get isUndoPossible() {

        return this._instancesHistory.length > 0;
    }


    /**
     * Revert the current model class state to the most recent of the saved snapshots.
     */
    undo() {

        if (this._instancesHistory.length > 0) {

            this._instance = (this._instancesHistory.pop() as T);
        }
    }


    /**
     * TODO - This method must be designed
     */
    redo() {

        // TODO
    }


    /**
     * Clear all the snapshots, and reset the model class instance to a new fresh instance.
     *
     * This operation is definitive. After this method is called, all history and the model class
     * instance current state will be lost forever.
     */
    reset() {

        this._instance = new this.typeConstructor();

        this._instancesHistory = [];
    }
}
