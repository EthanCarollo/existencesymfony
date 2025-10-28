import * as THREE from "three"

export class RendererManager {
    public renderer: THREE.WebGLRenderer
    private container: HTMLElement

    constructor(container: HTMLElement) {
        this.container = container
        const width = container.clientWidth
        const height = container.clientHeight

        this.renderer = new THREE.WebGLRenderer({ antialias: true })
        this.renderer.setSize(width, height)
        this.renderer.setPixelRatio(window.devicePixelRatio)
        container.appendChild(this.renderer.domElement)
    }

    public render(scene: THREE.Scene, camera: THREE.Camera) {
        this.renderer.render(scene, camera)
    }

    public onResize() {
        const width = this.container.clientWidth
        const height = this.container.clientHeight
        this.renderer.setSize(width, height)
    }

    public destroy() {
        this.renderer.dispose()
        if (this.renderer.domElement.parentElement) {
            this.renderer.domElement.parentElement.removeChild(this.renderer.domElement)
        }
    }
}
