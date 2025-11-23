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
    public selectedObject: any | null = null
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
            let model = this.modelLoader.getModelByKey(gridBuilding.building.model)
            let modelGLTFScene =  model.GLTF.scene.clone();
            this.scene.add(modelGLTFScene)
            let newPos = this.gridManager.gridToWorld(gridBuilding.xPos, gridBuilding.yPos)
            modelGLTFScene.position.set((newPos.x + (model.width / 2) - 0.5), -2, newPos.z - 0.5 + model.length / 2)
            let buildOnGrid = new BuildOnGrid(new THREE.Vector2(gridBuilding.xPos, gridBuilding.yPos), model, gridBuilding)
            this.objectOnGrid.push(buildOnGrid)
        })
    }

    public isBuildable(xPos: number, yPos: number): boolean {
        return true;
    }

    public setSelectedObject(build: any, event: MouseEvent): void {
        this.selectedObject = build
        this.selectedModel = (this.modelLoader.getModelByKey(build.model).GLTF.scene).clone()

        this.selectedModel.traverse((child) => {
            if (child instanceof THREE.Mesh) {
                child.material = child.material.clone()
                child.material.transparent = true
                child.material.opacity = 0.5
            }
        })

        this.scene.scene.add(this.selectedModel)
        if(event === null) return;
        const position = this.gridManager.getMouseGridPosition(
            event,
            this.scene.sceneContainer,
            this.cameraController.camera
        )
        if(position === null) { return; }
        this.targetPosition = new THREE.Vector3(
            position.x + this.modelLoader.getModelByKey(this.selectedObject.model).width / 2,
            -2,
            position.z + this.modelLoader.getModelByKey(this.selectedObject.model).length / 2,)
    }

    public async build(event: MouseEvent, token: any): Promise<any> {
        console.warn("Launch build")
        const position = this.gridManager.getMouseGridPosition(
            event,
            this.scene.sceneContainer,
            this.cameraController.camera
        )
        if(position === null) { return; }
        if(!this.isBuildable(position.x, position.z)) return;

        const posX = position.x
        const posY = position.z
        const idBuilding = this.selectedObject.id
        const buildingData = { xPos: posX, yPos: posY, buildingId: idBuilding }

        try {
            let model = this.modelLoader.getModelByKey(this.selectedObject.model)

            const newGridBuilding = await $fetch(useRuntimeConfig().public.backUrl + '/api/grid_buildings', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/ld+json',
                    'Accept': 'application/ld+json'
                },
                body: buildingData
            })

            let modelGLTFScene = model.GLTF.scene.clone()
            this.scene.scene.add(modelGLTFScene)
            let newPos = this.gridManager.gridToWorld(posX, posY)
            modelGLTFScene.position.set((newPos.x + (model.width / 2) - 0.5), -2, newPos.z - 0.5 + model.length / 2)
            let buildOnGrid = new BuildOnGrid(new THREE.Vector2(posX, posY), model, newGridBuilding)
            this.objectOnGrid.push(buildOnGrid)

            return { success: true, gridBuilding: newGridBuilding }
        } catch (error) {
            console.warn(error)
            return {
                success: false,
                error: error.data?.message || 'Erreur lors de la création du GridBuilding'
            }
        }
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
                position.x + this.modelLoader.getModelByKey(this.selectedObject.model).width / 2,
                -2,
                position.z + this.modelLoader.getModelByKey(this.selectedObject.model).length / 2,)
        }
    }

    public update(deltaTime: number) {
        if (this.selectedModel && this.targetPosition) {
            this.selectedModel.position.lerp(this.targetPosition, 0.1)
        }
    }
}