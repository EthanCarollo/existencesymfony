import * as THREE from "three"
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js"

export class CameraController {
    public camera: THREE.PerspectiveCamera
    private controls: OrbitControls

    constructor(width: number, height: number, renderer: THREE.WebGLRenderer) {
        this.camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000)
        this.camera.position.set(0, 5, 10)
        this.camera.lookAt(0, 0, 0)

        this.controls = new OrbitControls(this.camera, renderer.domElement)
        this.controls.enableDamping = true
        this.controls.dampingFactor = 0.05
        this.controls.screenSpacePanning = true
        this.controls.minDistance = 2
        this.controls.maxDistance = 50
        this.controls.maxPolarAngle = Math.PI / 2

        this.controls.mouseButtons = {
            LEFT: THREE.MOUSE.ROTATE,
            MIDDLE: THREE.MOUSE.DOLLY,
            RIGHT: THREE.MOUSE.PAN,
        }
    }

    public update() {
        this.controls.update()
    }

    public onResize(width: number, height: number) {
        this.camera.aspect = width / height
        this.camera.updateProjectionMatrix()
    }

    public destroy() {
        this.controls.dispose()
    }
}
