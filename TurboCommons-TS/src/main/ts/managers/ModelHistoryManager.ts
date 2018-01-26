/**
 * TurboCommons is a general purpose and cross-language library that implements frequently used and generic software development tasks.
 *
 * Website : -> http://www.turbocommons.org
 * License : -> Licensed under the Apache License, Version 2.0. You may not use this file except in compliance with the License.
 * License Url : -> http://www.apache.org/licenses/LICENSE-2.0
 * CopyRight : -> Copyright 2015 Edertone Advanded Solutions (08211 Castellar del Vallès, Barcelona). http://www.edertone.com
 */
 

import { ObjectUtils } from '../utils/ObjectUtils';
import { ArrayUtils } from '../utils/ArrayUtils';
import { StringUtils } from '../utils/StringUtils';


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
     * Basically this property configures the maximun number of 'undo' that are possible.
     */
    maxSnapshots = -1;
    
    
    /**
     * An instance that represents the state of the model at the very begining of its history.
     * If we perform all the possible undo operations, the current instance will
     * end being this one.
     */
    private _initialState: T;


    /**
     * An instance that represents the model state at this current moment
     */
    private _currentState: T;


    /**
     * A list with all the model instances that have been saved as snapshots
     */
    private _snapshots: T[] = [];


    /**
     * List with all the tags that have been applied to all the saved snapshots
     */
    private _snapshotTags: string[] = [];


    /**
     * This is a fully featured undo / redo manager.
     * It works with any class but is normally used with those that contain your application model data.
     * 
     * The first thing we need to do is to create a ModelHistoryManager and pass a model class instance which will be
     * used as the starting point of the history management. We can redefine the starting point at any time by calling
     * setInitialState() so if we need to perform some changes to the instance values, we can do it an fix it later as the
     * initial state.
     *
     * After defining the initial state, we will be able to save snapshots to track the changes on the instance,
     * and perform undo / redo operations at any time to restore the state to any of the previously saved snapshots.
     * 
     * We can get the instance at the current time by using the 'get' property.
     * 
     * @param instance An instance of the class model type to be used by the history manager as the starting point. 
     */
    constructor(instance: T) {
        
        this._currentState = instance;
        
        this._initialState = ObjectUtils.clone(this._currentState);
    }
    
    
    /**
     * Defines the current model state as the origin of the history management.
     * This means the current moment is considered as the starting point, and the last possible
     * undo operation will leave the model state as it was just when this method was called.
     * 
     * Note that calling this method also cleans any possible saved snapshots or history. We can define it as a
     * 'reset to the current moment' method.
     */
     setInitialState() {

         this._initialState = ObjectUtils.clone(this._currentState);
        
         this._snapshotTags = [];      
         this._snapshots = [];
    }


    /**
     * The model class instance as it is right now
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
     * Obtain a list with all the snapshots that where saved under a specific tag or tags.
     * 
     * Only the snapshots that match the given tag or tags will be returned, in the same order as they
     * were saved.
     * 
     * Each one of the array elements is a model class instance containing all
     * the information that was available at the moment of taking the snapshot
     * 
     * WARNING !! - This value must be used only to read data. Any direct modification of
     * the returned array will result in unwanted behaviours 
     * 
     * @param tags A list of strings with all the tags for which we want to obtain their related snapshots
     */
    getSnapshotsByTag(tags:string[]) {

        const errorMessage = 'tags must be a non empty string array. To get the full list of snapshots, use the <snapshots> property';

        if(!ArrayUtils.isArray(tags)){
            
            throw new Error(errorMessage);
        }
        
        if(tags.length <= 0){
            
            throw new Error(errorMessage);
        }
        
        let result = []; 
         
        for (var i = 0; i < this._snapshots.length; i++) {
    
            if(tags.indexOf(this._snapshotTags[i]) >= 0){
                
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
     * 
     * @returns true if a snapshot was saved, false if no snapshot saved (model has not changed)
     */
    saveSnapshot(tag = '') {

        if(!StringUtils.isString(tag)){
            
            throw new Error('tag must be a string');
        }
        
        // Check if the snapshot needs to be saved or not
        if(this._snapshots.length > 0 &&
                ObjectUtils.isEqualTo(this._currentState, this._snapshots[this._snapshots.length - 1])){
        
            return false;
        }
        
        if(this._snapshots.length <= 0 &&
                ObjectUtils.isEqualTo(this._currentState, this._initialState)){
        
            return false;
        }

        // If max undo limit is reached, remove first snapshot and set it as the initial state
        if(this.maxSnapshots > 0 && this._snapshots.length >= this.maxSnapshots){
            
            this._snapshotTags.shift();
            this._initialState = (this._snapshots.shift() as T);
        }
        
        this._snapshotTags.push(tag);
        this._snapshots.push(ObjectUtils.clone(this._currentState));
        
        return true;
    }


    /**
     * True if the current instance can be reverted to a previous state, false otherwise
     */
    get isUndoPossible() {

        if(this._snapshots.length > 0 ||
                !ObjectUtils.isEqualTo(this._currentState, this._initialState)){
        
            return true;
        }
        
        return false;
    }


    /**
     * Revert the current model class state to the most recent of the saved snapshots or to the initial state
     * if no snapshots are available.
     * 
     * If current state is the same as the initial state, undo will do nothing
     * 
     * @returns True if the undo operation resulted in a current state change, false otherwise
     */
    undo(): boolean {

        if (this._snapshots.length > 0) {
            
            this._snapshotTags.pop();
            let snapshot = (this._snapshots.pop() as T);
            
            // If current state has not changed from previous snapshot, we will call another undo
            if(ObjectUtils.isEqualTo(this._currentState, snapshot)){
                
                return this.undo();
            
            }else{
                
                this._currentState = snapshot;
                
                return true;
            }
        
        }else{
        
            if(!ObjectUtils.isEqualTo(this._currentState, this._initialState)){
                
                this._currentState = ObjectUtils.clone(this._initialState);
                
                return true;
            }
        }
        
        return false;
    }


    /**
     * Clear all the snapshots, and reset the model class instance to the initial state.
     *
     * This operation is definitive. After this method is called, all history and the current state
     * will be lost forever. No redo will be possible
     * 
     * @returns True if the current state changed, false otherwise
     */
    undoAll() {

        if(this.isUndoPossible){
            
            this._currentState = ObjectUtils.clone(this._initialState);
            
            this._snapshotTags = [];      
            this._snapshots = [];
            
            return true;
        }
        
        return false;
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
}
