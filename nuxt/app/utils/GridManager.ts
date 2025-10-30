import * as THREE from "three"

export interface GridPosition {
    x: number
    z: number
}

export class GridManager {
    private gridHelper: THREE.GridHelper
    private gridSize: number
    private gridDivisions: number
    private cellSize: number
    private highlightMesh: THREE.Mesh
    private intersectionPlane: THREE.Mesh // Added permanent plane for raycasting
    private raycaster: THREE.Raycaster
    private mouse: THREE.Vector2
    private currentGridPosition: GridPosition | null = null
    private tiles: THREE.Mesh[] = []
    private tileAnimationProgress = 0
    private isAnimating = true

    constructor(size = 100, divisions = 100) {
        this.gridSize = size
        this.gridDivisions = divisions
        this.cellSize = size / divisions

        this.gridHelper = new THREE.GridHelper(size, divisions, 0xcccccc, 0xd9d9d9)
        this.gridHelper.position.y = -2

        this.createTiles()

        const planeGeometry = new THREE.PlaneGeometry(size, size)
        const planeMaterial = new THREE.MeshBasicMaterial({
            visible: false,
            side: THREE.DoubleSide,
        })
        this.intersectionPlane = new THREE.Mesh(planeGeometry, planeMaterial)
        this.intersectionPlane.rotation.x = -Math.PI / 2
        this.intersectionPlane.position.y = -2

        const highlightGeometry = new THREE.PlaneGeometry(this.cellSize, this.cellSize)
        const highlightMaterial = new THREE.MeshBasicMaterial({
            color: 0x4a9eff,
            transparent: true,
            opacity: 0.4,
            side: THREE.DoubleSide,
        })
        this.highlightMesh = new THREE.Mesh(highlightGeometry, highlightMaterial)
        this.highlightMesh.rotation.x = -Math.PI / 2
        this.highlightMesh.position.y = -1.98
        this.highlightMesh.visible = false

        this.raycaster = new THREE.Raycaster()
        this.mouse = new THREE.Vector2()
    }

    private createTiles() {
        const tileGeometry = new THREE.PlaneGeometry(this.cellSize * 0.95, this.cellSize * 0.95)
        const tileMaterial = new THREE.MeshStandardMaterial({
            color: new THREE.Color(255, 255, 255),
            transparent: true,
            opacity: 0.2,
            side: THREE.DoubleSide,
        })

        const halfDivisions = this.gridDivisions / 2

        for (let x = -halfDivisions; x < halfDivisions; x++) {
            for (let z = -halfDivisions; z < halfDivisions; z++) {
                const tile = new THREE.Mesh(tileGeometry, tileMaterial.clone())
                tile.rotation.x = -Math.PI / 2

                const worldPos = this.gridToWorld(x, z)
                tile.position.set(worldPos.x, -2, worldPos.z)

                tile.userData.initialY = -2
                tile.userData.gridX = x
                tile.userData.gridZ = z

                this.tiles.push(tile)
            }
        }
    }

    public getGridHelper(): THREE.GridHelper {
        return this.gridHelper
    }

    public getHighlightMesh(): THREE.Mesh {
        return this.highlightMesh
    }

    public getIntersectionPlane(): THREE.Mesh {
        return this.intersectionPlane
    }

    public getTiles(): THREE.Mesh[] {
        return this.tiles
    }

    public setupMouseTracking(container: HTMLElement, camera: THREE.Camera) {
        container.addEventListener("mousemove", (event) => {
            const rect = container.getBoundingClientRect()
            this.mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
            this.mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1

            this.updateHighlight(camera)
        })

        container.addEventListener("mouseleave", () => {
            this.highlightMesh.visible = false
            this.currentGridPosition = null
        })
    }

    private updateHighlight(camera: THREE.Camera) {
        this.raycaster.setFromCamera(this.mouse, camera)

        const intersects = this.raycaster.intersectObject(this.intersectionPlane)

        if (intersects.length > 0) {
            const point = intersects[0].point

            const gridX = Math.floor(point.x / this.cellSize)
            const gridZ = Math.floor(point.z / this.cellSize)

            const worldX = gridX * this.cellSize + this.cellSize / 2
            const worldZ = gridZ * this.cellSize + this.cellSize / 2

            this.highlightMesh.position.x = worldX
            this.highlightMesh.position.z = worldZ
            this.highlightMesh.visible = true
            // console.warn(gridX + " - " + gridZ)
            this.currentGridPosition = { x: gridX, z: gridZ }
        } else {
            this.highlightMesh.visible = false
            this.currentGridPosition = null
        }
    }

    public getCurrentGridPosition(): GridPosition | null {
        return this.currentGridPosition
    }

    public gridToWorld(gridX: number, gridZ: number): { x: number; z: number } {
        return {
            x: gridX * this.cellSize + this.cellSize / 2,
            z: gridZ * this.cellSize + this.cellSize / 2,
        }
    }

    public worldToGrid(worldX: number, worldZ: number): GridPosition {
        return {
            x: Math.round(worldX / this.cellSize),
            z: Math.round(worldZ / this.cellSize),
        }
    }

    public getMouseGridPosition(event: MouseEvent, container: HTMLElement, camera: THREE.Camera): GridPosition | null {
        const rect = container.getBoundingClientRect()

        // Convert mouse position to normalized device coordinates (-1 to +1)
        const mouse = new THREE.Vector2()
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1

        // Set raycaster from camera
        this.raycaster.setFromCamera(mouse, camera)

        // Intersect with the invisible ground plane
        const intersects = this.raycaster.intersectObject(this.intersectionPlane)

        if (intersects.length > 0) {
            const point = intersects[0].point

            const gridX = Math.floor(point.x / this.cellSize)
            const gridZ = Math.floor(point.z / this.cellSize)

            return { x: gridX, z: gridZ }
        }

        return null
    }


    public destroy() {
        this.tiles.forEach((tile) => {
            tile.geometry.dispose()
            ;(tile.material as THREE.Material).dispose()
        })

        if (this.highlightMesh) {
            this.highlightMesh.geometry.dispose()
            ;(this.highlightMesh.material as THREE.Material).dispose()
        }
        if (this.intersectionPlane) {
            this.intersectionPlane.geometry.dispose()
            ;(this.intersectionPlane.material as THREE.Material).dispose()
        }
        this.gridHelper.geometry.dispose()
        ;(this.gridHelper.material as THREE.Material).dispose()
    }
}
