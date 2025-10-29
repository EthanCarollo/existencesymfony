import * as THREE from "three";
import { GLTFLoader } from "three/examples/jsm/loaders/GLTFLoader.js";

export class ModelLoader {
    private loader: GLTFLoader;
    private cache: Map<string, GLTF>;
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
            await this.loadModel(model.url, model.key)
        }
    }

    async loadModel(url: string, key?: string): Promise<GLTF> {
        if (this.cache.has(url)) {
            const cloned = this.cloneGLTF(this.cache.get(url)!);
            if (key) this.keyMap.set(key, url);
            return cloned;
        }

        const model = await new Promise<GLTF>((resolve, reject) => {
            this.loader.load(
                url,
                (gltf) => {
                    this.cache.set(url, gltf);
                    if (key) this.keyMap.set(key, url);
                    resolve(this.cloneGLTF(gltf));
                },
                undefined,
                reject
            );
        });

        return model;
    }

    getModelByKey(key: string): GLTF | undefined {
        const url = this.keyMap.get(key);
        if (!url) return undefined;
        const gltf = this.cache.get(url);
        return gltf ? this.cloneGLTF(gltf) : undefined;
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
