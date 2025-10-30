import * as THREE from 'three';

export class BuildOnGrid {
    position: THREE.Vector2;
    object: LoadedObject;
    dataObject: object;

    constructor(position: THREE.Vector2, object: LoadedObject, dataObject: object) {
        this.position = position;
        this.object = object;
        this.dataObject = dataObject;
    }
}
