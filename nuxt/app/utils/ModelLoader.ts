import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";

interface LoadedObject {
    "GLTF": GLTF,
    "width": number
    "height": number
    "length": number
}

export class ModelLoader {
    private loader: GLTFLoader;
    private cache: Map<string, LoadedObject>;
    private keyMap: Map<string, string>;
    private manager: THREE.LoadingManager;

    constructor(manager?: THREE.LoadingManager) {
        this.manager = manager || new THREE.LoadingManager();
        this.loader = new GLTFLoader(this.manager);
        this.loader.setRequestHeader()
        this.loader.crossOrigin = 'anonymous';
        this.cache = new Map();
        this.keyMap = new Map();
    }

    async loadModels(modelsWithKey: any): Promise<void> {
        for (const model of modelsWithKey) {
            await this.loadModel(model.url, model.key, model.width, model.height, model.length);
        }
        console.warn(`Loaded models ${JSON.stringify(modelsWithKey)}`);
    }

    async loadModel(url: string, key?: string, width: number, height: number, length: number): Promise<GLTF> {
        if (this.cache.has(url)) {
            const cloned = this.cloneGLTF(this.cache.get(url)!);
            if (key) this.keyMap.set(key, url);
            return cloned;
        }

        const model = await new Promise<GLTF>((resolve, reject) => {
            this.loader.load(
                url,
                (gltf) => {
                    this.resizeModel(gltf.scene, width, height, length)
                    const modelInCache: LoadedObject= {
                        "GLTF": gltf,
                        "width": width,
                        "length": length,
                        "height": height,
                    }
                    this.cache.set(url, modelInCache);
                    if (key) this.keyMap.set(key, url);
                    let gltfCloned = this.cloneGLTF(gltf)
                    resolve(gltfCloned);
                },
                undefined,
                reject
            );
        });
        console.warn(model.scene)
        return model;
    }

    resizeModel(model, targetWidth, targetHeight, targetLength) {
        const box = new THREE.Box3().setFromObject(model);
        const size = new THREE.Vector3();
        box.getSize(size); // => { x: largeur, y: hauteur, z: longueur }
        const scaleX = targetWidth / size.x;
        const scaleY = targetHeight / size.y;
        const scaleZ = targetLength / size.z;
        model.scale.set(scaleX, scaleY, scaleZ);
        const center = new THREE.Vector3();
        box.getCenter(center);
        model.position.sub(center.multiply(model.scale));
    }

    getModelByKey(key: string): GLTF | undefined {
        const url = this.keyMap.get(key);
        if (!url) return undefined;
        const gltf = this.cache.get(url);
        return gltf ? gltf : undefined;
    }

    async preload(urls: string[]): Promise<void> {
        await Promise.all(urls.map((url) => this.loadModel(url)));
    }

    clearCache(): void {
        this.cache.clear();
        this.keyMap.clear();
    }

    private cloneGLTF(gltf: GLTF): GLTF {
        const clonedScene = gltf.scene.clone(true);
        return { ...gltf, scene: clonedScene };
    }
}
