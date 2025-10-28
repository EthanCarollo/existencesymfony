import * as THREE from "three"
import { CameraController } from "./CameraController"
import { RendererManager } from "./RendererManager"
import { GridManager } from "./GridManager"

export default class Scene {
    private scene: THREE.Scene
    private cameraController: CameraController | null = null
    private rendererManager: RendererManager | null = null
    private gridManager: GridManager | null = null
    private animationId: number | null = null

    constructor() {
        this.scene = new THREE.Scene()
        this.scene.background = new THREE.Color(0xf5f5f5)
        this.scene.fog = new THREE.Fog(0xf5f5f5, 10, 50)
    }

    public mounted(sceneContainer: HTMLElement) {
        const width = sceneContainer.clientWidth
        const height = sceneContainer.clientHeight

        this.rendererManager = new RendererManager(sceneContainer)
        this.cameraController = new CameraController(width, height, this.rendererManager.renderer)

        this.gridManager = new GridManager(100, 100)
        this.scene.add(this.gridManager.getGridHelper())
        this.scene.add(this.gridManager.getHighlightMesh())
        this.scene.add(this.gridManager.getIntersectionPlane()) // Added intersection plane to scene

        this.gridManager.setupMouseTracking(sceneContainer, this.cameraController?.camera) // Removed scene parameter

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.8)
        this.scene.add(ambientLight)

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5)
        directionalLight.position.set(5, 10, 5)
        this.scene.add(directionalLight)

        this.animate()
        window.addEventListener("resize", () => this.onResize(sceneContainer))
    }

    private animate = () => {
        this.animationId = requestAnimationFrame(this.animate)

        this.cameraController?.update()

        this.rendererManager?.render(this.scene, this.cameraController!.camera)
    }

    private onResize(container: HTMLElement) {
        const width = container.clientWidth
        const height = container.clientHeight

        this.cameraController?.onResize(width, height)
        this.rendererManager?.onResize()
    }

    public destroy() {
        if (this.animationId) cancelAnimationFrame(this.animationId)

        this.cameraController?.destroy()
        this.rendererManager?.destroy()
        this.gridManager?.destroy()
        // window.removeEventListener("resize", this.onResize)
    }
}
