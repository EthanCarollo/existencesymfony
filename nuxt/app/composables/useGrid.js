import { useGridStore } from "~/stores/grid.js";

export const useGrid = () => {
    const gridStore = useGridStore()

    const fetchGrid = async (token) => {
        return await gridStore.fetchGrid(token)
    }

    return {
        fetchGrid,
    }
}