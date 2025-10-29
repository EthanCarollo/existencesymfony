import * as THREE from "three"

export class BuildManager {
    private raycaster: THREE.Raycaster
    private mouse: THREE.Vector2
    private plane: THREE.Plane
    public selectedModel: THREE.Object3D | null = null
    public selectedObject: string | null = null
    private targetPosition: THREE.Vector3 | null = null

    constructor(
        private grid: GridManager,
        private modelLoader: ModelLoader,
        private cameraController: CameraController,
        private scene: Scene
    ) {
        this.raycaster = new THREE.Raycaster()
        this.mouse = new THREE.Vector2()
        this.plane = new THREE.Plane(new THREE.Vector3(0, 1, 0), 0)
        window.addEventListener("mousemove", this.onMouseMove.bind(this))
    }

    public setSelectedObject(name: string) {
        this.selectedObject = name
        this.selectedModel = this.modelLoader.getModelByKey(name).GLTF.scene
        this.scene.scene.add(this.selectedModel)
    }

    public resetSelectedObject() {
        if(this.selectedModel !== null) this.scene.scene.remove(this.selectedModel)
        this.selectedObject = null
        this.selectedModel = null
    }

    private onMouseMove(event: MouseEvent) {
        if (!this.selectedObject) return
        const position = this.grid.getMouseGridPosition(
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
