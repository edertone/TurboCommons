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
     * Specifies the maximum amount of snapshots that will be saved.
     * If we try to save a snapshot and there are more than the ones specified here, the oldest one
     * will be deleted and the next will be defined as the initial state.
     * 
     * Setting it to -1 (default) means infinite snapshots are possible.
     * 
     * Basically this property configures the maximun number of possible undos
     */
    maxSnapshots = -1;
    
    
    /**
     * An instance of the provided model class type that represents the state of the model at the very
     * begining of its history. If we perform all the possible undo operations, the current instance will
     * end being this one.
     */
    private _initialState: T;


    /**
     * An instance of the provided model class type that represents the current model state
     */
    private _currentState: T;


    /**
     * A list with all the model instances that are saved as snapshots
     */
    private _snapshots: T[] = [];


    /**
     * List with all the tags that have been applied to all the saved snapshots.
     */
    private _snapshotTags: string[] = [];


    /**
     * This is a fully featured undo / redo manager for any model class
     *
     * When ModelHistoryManager is created, we provide the class type we want to instantiate. ModelHistoryManager
     * takes the responsability of creating the class instance, and internally track any of the changes.
     *
     * We will then be able to save snapshots and track the changes on the model class instance,
     * so we can perform undo and redo operations at any time to restore the class state to any of
     * the previously saved snapshots.
     * 
     * We can get the instance at the current time by using the 'get' property.
     *
     * @param modelClassType The class type that will be used by the ModelHistoryManager. A new fresh instance
     * is inmediately created by this constructor and used as the initial state.
     */
    constructor(private modelClassType: new () => T) {

        this.reset();
    }


    /**
     * The model class instance as it is now
     */
    get get(): T {

        return this._currentState;
    }


    /**
     * Array containing all the snapshots that have been saved till the current
     * moment. Each one of the array elements is a model class instance containing all
     * the information that was available at the moment of taking the snapshot
     * 
     * WARNING !! - This value must be used only to read data. Any direct modification of
     * the returned array will result in unwanted behaviours 
     */
    get snapshots() {

        return this._snapshots;
    }

    
    /**
     * Defines the current model instance as the begining of the history management.
     * This means the current model state will be considered as the starting point, and all undo
     * operations will end here.
     * 
     * Calling this method also cleans all the saved snapshots, so in fact this method is used to
     * generate a starting point of all the managed history
     */
     setInitialState() {

        this._snapshotTags = [];      
        this._snapshots = [];
        
        this._initialState = ObjectUtils.clone(this._currentState);
    }
    
    
    /**
     * Obtain a list with all the snapshots that where saved under a specific tag or tags.
     * 
     * Only those snapshots that match the given tag or tags will be returned, in the same order as they
     * were saved.
     * 
     * Each one of the array elements is a model class instance containing all
     * the information that was available at the moment of taking the snapshot
     * 
     * WARNING !! - This value must be used only to read data. Any direct modification of
     * the returned array will result in unwanted behaviours 
     * 
     * @param tag  A list of strings with all the tags for which we want to obtain their related snapshots
     */
     getSnapshotsByTag(tag:string[]) {

         let result = []; 
         
         for (var i = 0; i < this._snapshots.length; i++) {
    
            if(tag.indexOf(this._snapshotTags[i]) >= 0){
                
                result.push(this._snapshots[i]);
            }
         }
        
        return result;
    }
     

    /**
     * Save a copy of the current model class instance state so it can be retrieved later.
     * 
     * @param tag A string we can use as 'label' or 'name' for the saved snapshot. This is useful if
     * we later want to get a filtered list of snapshots
     */
    saveSnapshot(tag = '') {

        // If max undo limit is reached, remove first snapshot and set it as the initial state
        if(this.maxSnapshots > 0 && this._snapshots.length >= this.maxSnapshots){
            
            this._snapshotTags.shift();
            this._initialState = this._snapshots.shift();
        }

        this._snapshotTags.push(tag);
        this._snapshots.push(ObjectUtils.clone(this._currentState));
    }


    /**
     * True if any snapshot of the model class instance exist, false otherwise
     */
    get isUndoPossible() {

        if(this._snapshots.length > 0){
            
            return true;
        }
        
        if(!ObjectUtils.isEqualTo(this._currentState, this._initialState)){
        
            return true;
        }
        
        return false;
    }


    /**
     * Revert the current model class state to the most recent of the saved snapshots or to the initial state
     */
    undo() {

        if (this._snapshots.length > 0) {
            
            this._snapshotTags.pop();        
            this._currentState = (this._snapshots.pop() as T);
        
        }else{
            
            this._currentState = ObjectUtils.clone(this._initialState);
        }
    }


    /**
     * TODO - This method must be designed
     */
    redo() {

        // TODO
        // It seems that Proxy feature of ES6 allows us to detect object chages
        // so we will be able to disable the redo after current state is modified.
        // http://blog.revathskumar.com/2016/02/es6-observe-change-in-object-using-proxy.html
        // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Proxy
    }


    /**
     * Clear all the snapshots, and reset the model class instance to a new unmodified model class instance.
     *
     * This operation is definitive. After this method is called, all history and the model class
     * instance current state will be lost forever and the current model state will be reset to a clean instance.
     */
    reset() {

        this._currentState = new this.modelClassType();
        this._initialState = new this.modelClassType();

        this._snapshotTags = [];      
        this._snapshots = [];
    }
}
