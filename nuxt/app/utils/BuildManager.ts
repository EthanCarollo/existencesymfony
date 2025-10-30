import * as THREE from "three"
import type {GLTF} from "three/examples/jsm/loaders/GLTFLoader";
import type {LoadedObject} from "~/utils/ModelLoader";
import ConstructedBuildings from "~/components/sidebar/ConstructedBuildings.vue";
import {BuildOnGrid} from "~/utils/build/BuildOnGrid";

export class BuildManager {
    private raycaster: THREE.Raycaster
    private mouse: THREE.Vector2
    private plane: THREE.Plane
    public selectedModel: THREE.Object3D | null = null
    public selectedObject: string | null = null
    private targetPosition: THREE.Vector3 | null = null
    public objectOnGrid: BuildOnGrid[] = [];

    constructor(
        private gridManager: GridManager,
        private modelLoader: ModelLoader,
        private cameraController: CameraController,
        private scene: Scene,
        private grid: any
    ) {
        this.raycaster = new THREE.Raycaster()
        this.mouse = new THREE.Vector2()
        this.plane = new THREE.Plane(new THREE.Vector3(0, 1, 0), 0)
        window.addEventListener("mousemove", this.onMouseMove.bind(this))
        this.initializeDefaultObjects()
    }

    public initializeDefaultObjects() {
        let gridBuildings: any[] = this.grid.gridBuildings
        gridBuildings.forEach((gridBuilding: any) => {
            // Instantiate Object
            let model = this.modelLoader.getModelByKey(gridBuilding.building.model)
            let modelGLTFScene =  model.GLTF.scene.clone();
            this.scene.add(modelGLTFScene)
            // Set Object position
            let newPos = this.gridManager.gridToWorld(gridBuilding.xPos, gridBuilding.yPos)
            modelGLTFScene.position.set((newPos.x + (model.width / 2) - 0.5), -2, newPos.z - 0.5 + model.length / 2)
            // Add Object in object map
            let buildOnGrid = new BuildOnGrid(new THREE.Vector2(gridBuilding.xPos, gridBuilding.yPos), model, gridBuilding)
            this.objectOnGrid.push(buildOnGrid)
        })
    }

    public isBuildable(xPos: number, yPos: number): boolean {
        // TODO : WIP
        return true;
    }

    public setSelectedObject(name: string, event: MouseEvent): void {
        this.selectedObject = name
        this.selectedModel = (this.modelLoader.getModelByKey(name).GLTF.scene).clone()
        this.scene.scene.add(this.selectedModel)
        if(event === null) return;
        const position = this.gridManager.getMouseGridPosition(
            event,
            this.scene.sceneContainer,
            this.cameraController.camera
        )
        console.warn(position)
        this.targetPosition = new THREE.Vector3(
            position.x + this.modelLoader.getModelByKey(this.selectedObject).width / 2,
            -2,
            position.z + this.modelLoader.getModelByKey(this.selectedObject).length / 2,)
    }

    public resetSelectedObject() {
        if(this.selectedModel !== null) this.scene.scene.remove(this.selectedModel)
        this.selectedObject = null
        this.selectedModel = null
    }

    private onMouseMove(event: MouseEvent) {
        if (!this.selectedObject) return
        const position = this.gridManager.getMouseGridPosition(
            event,
            this.scene.sceneContainer,
            this.cameraController.camera
        )
        if (position) {
            this.targetPosition = new THREE.Vector3(
                position.x + this.modelLoader.getModelByKey(this.selectedObject).width / 2,
                -2,
                position.z + this.modelLoader.getModelByKey(this.selectedObject).length / 2,)
        }
    }

    public update(deltaTime: number) {
        if (this.selectedModel && this.targetPosition) {
            this.selectedModel.position.lerp(this.targetPosition, 0.1)
        }
    }
}
